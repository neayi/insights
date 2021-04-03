<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;

class Interaction
{
    private $follow;
    private $applause;
    private $done;
    private $pageId;

    public function __construct(
        int $pageId,
        bool $follow,
        bool $applause,
        bool $done
    )
    {
        $this->pageId = $pageId;
        $this->done = $done;
        $this->follow = $follow;
        $this->applause = $applause;
    }

    public function pageId():int
    {
        return $this->pageId;
    }

    public function update(array $interactions)
    {
        foreach ($interactions as $interaction){
            switch ($interaction){
                case 'follow':
                    $this->follow = true;
                    break;
                case 'unfollow':
                    $this->follow = false;
                    break;
                case 'done':
                    $this->done = true;
                    break;
                case 'undone':
                    $this->done = false;
                    break;
                case 'applause':
                    $this->applause = true;
                    break;
                case 'unapplause':
                    $this->applause = false;
                    break;
            }
        }
    }
}
