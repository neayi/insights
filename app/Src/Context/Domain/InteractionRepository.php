<?php


namespace App\Src\Context\Domain;


use Illuminate\Contracts\Pagination\Paginator;

interface InteractionRepository
{
    public function save(CanInteract $canInteract, Interaction $interaction);
    public function getByInteractUser(CanInteract $canInteract, int $pageId):?Interaction;
    public function transfer(CanInteract $anonymous, CanInteract $registered);
    public function getCountInteractionsOnPage(int $pageId):array;
    public function getInteractionsByUser(string $userId):array;
    public function getDoneByUser(string $userId):array;


    public function getFollowersPage(
        int $pageId,
        string $type = 'follow',
        ?string $dept = null,
        ?string $characteristicId = null,
        ?string $characteristicIdCroppingSystem = null
    ):Paginator;
}