<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class GetLastWikiUserComments
{
    public function __construct(
        private UserRepository $userRepository,
        private ForumApiClient $forumApiClient
    ){}

    public function get(string $userId)
    {
        $comments = Cache::get("comments_".$userId);
        if (isset($comments)) {
            return json_decode($comments, true);
        }

        $user = $this->userRepository->getById($userId);

        $localeConfig = LocalesConfig::query()->where('code', $user->wiki())->first();
        $forumURL = $localeConfig->forum_url;

        $content = $this->forumApiClient->getUserByUsername($user->discourse_username());
        $commentsToRetrieved = [];

        if (!empty($content['posts'])) {
            $comments = $content['posts'];

            $topicsTitleById = [];
            $topics = $content['topics'];
            foreach ($topics as $aTopic) {
                $topicsTitleById[$aTopic['id']] = $aTopic['title'];
            }

            foreach ($comments as $aPost) {
                $commentsToRetrieved[$aPost['created_at']] = [
                    'html' => $aPost['blurb'],
                    'title' => $topicsTitleById[$aPost['topic_id']],
                    'url' => $forumURL . '/t/'.$aPost['topic_id'].'/'.$aPost['post_number'],
                    'date' => (new Carbon($aPost['created_at']))->translatedFormat('l j F Y - h:i')
                ];
            }

            // sort the comments
            ksort($commentsToRetrieved);
            $commentsToRetrieved = array_reverse($commentsToRetrieved);
        }

        Cache::put("comments_".$userId, json_encode($commentsToRetrieved), 86400);
        return $commentsToRetrieved;
    }
}
