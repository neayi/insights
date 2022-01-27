<?php


namespace App\Src\Insights\Insights\Application\Read\Context;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class GetLastWikiUserComments
{
    private $httpClient;
    private $commentsEndPoint = '?action=query&list=usercomments&format=json&ucuserguids=';

    public function __construct(UserRepository $userRepository)
    {
        $this->httpClient = new Client();
        $this->userRepository = $userRepository;
    }

    public function get(string $userId)
    {
        $comments = Cache::get("comments_".$userId);
        if(isset($comments)){
            return json_decode($comments, true);
        }

        $response = $this->httpClient->get(config('wiki.api_uri').$this->commentsEndPoint.$userId);
        $content = json_decode($response->getBody()->getContents(), true);
        $comments = $content['query']['usercomments'];

        $commentsToRetrieved = [];
        foreach($comments as $comment){
            if(!isset($commentsToRetrieved[$comment['pageid']])) {
                $commentsToRetrieved[$comment['pageid']] = $comment;

                $realPageId = $comment['associatedid'];
                $page = PageModel::where('page_id', $realPageId)->first();
                $commentsToRetrieved[$comment['pageid']]['picture'] = $page['picture'];
                $commentsToRetrieved[$comment['pageid']]['real_page_id'] = $realPageId;

                $commentsToRetrieved[$comment['pageid']]['title'] = $comment['associated_page_title'];
                $commentsToRetrieved[$comment['pageid']]['date'] = (new Carbon($comment['timestamp']))->translatedFormat('l j F Y - h:i');
            }
        }

        Cache::put("comments_".$userId, json_encode($commentsToRetrieved), 86400);
        return $commentsToRetrieved;
    }
}
