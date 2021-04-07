<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Agricultural\Queries\CountInteractionsOnPageQuery;
use App\Src\UseCases\Domain\Agricultural\Queries\InteractionsQueryByPageAndUser;
use App\Src\UseCases\Domain\Users\Interactions\HandleInteractions;
use Illuminate\Http\Request;

/**
 * @group Interaction management
 *
 * APIs for interaction on pages
 */
class InteractionController extends Controller
{

    /**
     * Add a interaction (follow, unfollow, done, undone, applause, unapplause) of the user authenticated to the page given
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam wiki_session_id string required The wiki session id Example:abc
     * @bodyParam interactions string[] required The user's interactions on the page. Example: unapplause, done
     */
    public function handle($pageId, Request $request, HandleInteractions $handleInteractions, InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser)
    {
        $interactions = $request->input('interactions');
        $doneValue = $request->input('done_value', []);
        $handleInteractions->execute($pageId, $interactions, $doneValue);

        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        return isset($interaction) ? $interaction->toArray() : [];
    }

    /**
     * Get the number of interactions for the page given
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam wiki_session_id string required The wiki session id Example:abc
     */
    public function countsInteractionOnPage($pageId, CountInteractionsOnPageQuery $countInteractionsOnPage):array
    {
        return $countInteractionsOnPage->execute($pageId);
    }

    /**
     * Get the state of interaction for the user authenticated on the page given
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam wiki_session_id string required The wiki session id Example:abc
     */
    public function getInteractionsOnPageByUser($pageId, InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser)
    {
        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        return isset($interaction) ? $interaction->toArray() : [];
    }
}
