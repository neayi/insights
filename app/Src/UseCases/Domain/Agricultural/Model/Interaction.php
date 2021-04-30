<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;

class Interaction
{
    private $follow;
    private $applause;
    private $done;
    private $pageId;
    private $doneValue;

    public function __construct(
        int $pageId,
        bool $follow,
        bool $applause,
        bool $done,
        array $doneValue = []
    )
    {
        $this->pageId = $pageId;
        $this->done = $done;
        $this->follow = $follow;
        $this->applause = $applause;
        $this->doneValue = $doneValue;
    }

    public function pageId():int
    {
        return $this->pageId;
    }

    public function update(array $interactions, array $doneValue = [])
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
                    $this->doneValue = $doneValue;
                    break;
                case 'undone':
                    $this->done = false;
                    $this->doneValue = null;
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

    public function toArray():array
    {
        return [
            'done' => $this->done,
            'follow' => $this->follow,
            'applause' => $this->applause,
            'value' => $this->doneValue,
            'page_id' => $this->pageId
        ];
    }
}
