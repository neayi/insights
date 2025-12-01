<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LocalesConfig;
use App\Src\WikiSemanticApiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportPageDetailsToWiki extends Command
{
    protected $signature = 'pages:export-details-to-wiki {--since-x-days=15}';

    protected $description = 'Envoie des détails des pages vers le wiki';

    public function handle(): void
    {
        $dateThreshold = Carbon::now()->sub(sprintf('%d days', $this->option('since-x-days')))->setTime(0, 0, 0);

        $localesConfig = LocalesConfig::all();

        foreach ($localesConfig as $localeConfig) {
            // On ne traite que le wiki FR pour l'instant
            if ('fr' !== $localeConfig->code) {
                continue;
            }

            try {
                $this->handleExportPageLikes($localeConfig, $dateThreshold);
            } catch (\Throwable $e) {
                $this->error(get_class($e));
                $this->error(sprintf('Error exporting page details to wiki %s: %s', $localeConfig->code, $e->getMessage()));
            }
        }
    }

    private function handleExportPageLikes(LocalesConfig $localeConfig, Carbon $dateThreshold): void
    {
        $client = new WikiSemanticApiClient($localeConfig->toArray());
        $wikiCode = $localeConfig->code;

        $this->info(sprintf('Exporting Pages "Likes" amount to wiki %s...', $wikiCode));

        $sql = <<<SQL
            SELECT p.title, p.wiki, COUNT(i.id) AS likes_amount
            FROM pages p
                INNER JOIN interactions i ON i.page_id = p.page_id AND i.wiki = p.wiki AND i.follow = 1
            WHERE p.wiki = :wikiCode
            GROUP BY p.title, p.wiki
            HAVING MAX(i.updated_at) >= :dateThreshold
        SQL;
        $rawResults = DB::select(
            $sql,
            ['wikiCode' => $wikiCode, 'dateThreshold' => $dateThreshold->format('Y-m-d H:i:s')]
        );

        foreach ($rawResults as $eligiblePage) {
            try {
                $success = $client->postPageLikesAmount($eligiblePage->title, $eligiblePage->likes_amount);

                if (!$success) {
                    $this->error(
                        sprintf(
                            'Failed to export Likes (%d) for page "%s" (%s)',
                            $eligiblePage->likes_amount,
                            $eligiblePage->title,
                            $eligiblePage->wiki
                        )
                    );
                } else {
                    $this->info(
                        sprintf(
                            'Successfully exported Likes (%d) for page "%s" (%s)',
                            $eligiblePage->likes_amount,
                            $eligiblePage->title,
                            $eligiblePage->wiki
                        )
                    );
                }
            } catch (\Throwable $e) {
                $this->error(
                    sprintf(
                        'Error exporting Likes (%d) for page "%s" (%s): %s',
                        $eligiblePage->likes_amount,
                        $eligiblePage->title,
                        $eligiblePage->wiki,
                        $e->getMessage()
                    )
                );
            }
        }
    }
}
