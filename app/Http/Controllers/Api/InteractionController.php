<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Agricultural\Queries\CountInteractionsOnPageQuery;
use App\Src\UseCases\Domain\Agricultural\Queries\InteractionsQueryByPageAndUser;
use App\Src\UseCases\Domain\Users\Interactions\HandleInteractions;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function handle($pageId, Request $request, HandleInteractions $handleInteractions, InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser)
    {
        $interactions = $request->input('interactions');
        $doneValue = $request->input('done_value', []);
        $handleInteractions->execute($pageId, $interactions, $doneValue);

        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        return isset($interaction) ? $interaction->toArray() : [];
    }

    public function countsInteractionOnPage($pageId, CountInteractionsOnPageQuery $countInteractionsOnPage):array
    {
        return $countInteractionsOnPage->execute($pageId);
    }

    public function getInteractionsOnPageByUser($pageId, InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser)
    {
        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        return isset($interaction) ? $interaction->toArray() : [];
    }
}
