<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Context\Queries\CountInteractionsOnPageQuery;
use App\Src\UseCases\Domain\Context\Queries\GetFollowersOfPage;
use App\Src\UseCases\Domain\Context\Queries\GetStatsByDepartment;
use App\Src\UseCases\Domain\Context\Queries\GetInteractionsByPageAndUser;
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
     * Add an interaction (follow, unfollow, done, undone, applause, unapplause) of the user authenticated to the page given
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam wiki_session_id string required The wiki session id Example:abc
     * @bodyParam interactions string[] required The user's interactions on the page. Example: unapplause, done
     */
    public function handle(
        $pageId,
        Request $request,
        HandleInteractions $handleInteractions,
        GetInteractionsByPageAndUser $interactionsQueryByPageAndUser,
        CountInteractionsOnPageQuery $countInteractionsOnPage
    )
    {
        $interactions = $request->input('interactions');
        $doneValue = $request->input('done_value', []);
        $wikiCode = $request->input('wiki');
        $handleInteractions->execute((int)$pageId, $interactions, $doneValue, $wikiCode);

        $interaction = $interactionsQueryByPageAndUser->execute((int)$pageId, $wikiCode);
        $counts = $countInteractionsOnPage->execute((int)$pageId, $wikiCode);

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
    public function countsInteractionOnPage($pageId, Request $request, CountInteractionsOnPageQuery $countInteractionsOnPage):array
    {
        $wikiCode = $request->input('wiki');

        return $countInteractionsOnPage->execute((int)$pageId, $wikiCode);
    }

    /**
     * Get the state of interaction for the user authenticated on the page given
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam wiki_session_id string required The wiki session id Example:abc
     */
    public function getInteractionsOnPageByUser(
        $pageId,
        Request $request,
        GetInteractionsByPageAndUser $interactionsQueryByPageAndUser,
        CountInteractionsOnPageQuery $countInteractionsOnPage
    )
    {
        $wikiCode = $request->input('wiki');
        $interaction = $interactionsQueryByPageAndUser->execute((int)$pageId, $wikiCode);
        $counts = $countInteractionsOnPage->execute((int)$pageId, $wikiCode);

        return [
            'state' => isset($interaction) ? $interaction->toArray() : [],
            'counts' => $counts
        ];
    }

    /**
     * Get the followers of the page
     * @urlParam pageId integer required The wiki page id Example:1
     * @queryParam type string The type of interactions Example:follow,do
     * @queryParam dept string A department number. Example: 83, 2A
     * @queryParam farming_id string The uuid of a farming characteristic
     * @queryParam cropping_id string The uuid of a cropping characteristic
     */
    public function followersOfPage($pageId, Request $request, GetFollowersOfPage $getFollowersOfPage)
    {
        $type = $request->input('type', 'follow');
        $dept = $request->input('dept', null);
        $farmingId = $request->input('farming_id', null);
        $croppingId = $request->input('cropping_id', null);
        $wikiCode = $request->input('wiki');

        return $getFollowersOfPage->execute((int)$pageId, $type, $dept, $farmingId, $croppingId, $wikiCode);
    }

    public function getStatsDepartment($pageId, Request $request, GetStatsByDepartment $getStatsByDepartment)
    {
        $type = $request->input('type', 'follow');

        return $getStatsByDepartment->execute((int)$pageId, $type);
    }
}
