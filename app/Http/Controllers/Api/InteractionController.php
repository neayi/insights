<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\CountInteractionsOnPageQuery;
use App\Src\UseCases\Domain\Context\Queries\GetFollowersOfPage;
use App\Src\UseCases\Domain\Context\Queries\GetStatsByDepartment;
use App\Src\UseCases\Domain\Context\Queries\InteractionsQueryByPageAndUser;
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
    public function handle(
        $pageId,
        Request $request,
        HandleInteractions $handleInteractions,
        InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser,
        CountInteractionsOnPageQuery $countInteractionsOnPage
    )
    {
        $interactions = $request->input('interactions');
        $doneValue = $request->input('done_value', []);
        $handleInteractions->execute($pageId, $interactions, $doneValue);

        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        $counts = $countInteractionsOnPage->execute($pageId);
        return [
            'state' => isset($interaction) ? $interaction->toArray() : [],
            'counts' => $counts
        ];
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
    public function getInteractionsOnPageByUser(
        $pageId,
        InteractionsQueryByPageAndUser $interactionsQueryByPageAndUser,
        CountInteractionsOnPageQuery $countInteractionsOnPage
    )
    {
        $interaction = $interactionsQueryByPageAndUser->execute($pageId);
        $counts = $countInteractionsOnPage->execute($pageId);
        return [
            'state' => isset($interaction) ? $interaction->toArray() : [],
            'counts' => $counts
        ];
    }

    /**
     * Get the followers of the page
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam type string The type of interactions Example:follow,do
     * @queryParam dept string The user's postal code. Example: 83220
     * @queryParam farming_id string The uuid of a farming characteristic
     * @queryParam cropping_id string The uuid of a cropping characteristic
     */
    public function followersOfPage($pageId, Request $request, GetFollowersOfPage $getFollowersOfPage)
    {
        $type = $request->input('type', 'follow');
        $dept = $request->input('dept', null);
        $farmingId = $request->input('farming_id', null);
        $croppingId = $request->input('cropping_id', null);
        return $getFollowersOfPage->execute($pageId, $type, $dept, $farmingId, $croppingId);
    }

    public function getStatsDepartment($pageId, Request $request, GetStatsByDepartment $getStatsByDepartment)
    {
        $type = $request->input('type', 'follow');
        return $getStatsByDepartment->execute($pageId, $type);
    }
}
