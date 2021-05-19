<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class GetLastWikiUserComments
{
    private $httpClient;
    private $userRepository;
    private $commentsEndPoint = '?action=query&list=usercontribs&ucnamespace=844&format=json&ucuser=';
    private $commentEndPoint = '?action=csquerycomment&format=json&pageid=';

    public function __construct(UserRepository $userRepository)
    {
        $this->httpClient = new Client();
        $this->userRepository = $userRepository;
    }

    public function get(string $userId)
    {
        $user = $this->userRepository->getById($userId);
        $userData = $user->toArray();
        $username = ucfirst($userData['firstname']).'_'.ucfirst($userData['lastname']);
        $comments = Cache::get("comments_".$userId);
        if(isset($comments)){
            return json_decode($comments, true);
        }

        $response = $this->httpClient->get(config('wiki.api_uri').$this->commentsEndPoint.$username);
        $content = json_decode($response->getBody()->getContents(), true);
        $comments = $content['query']['usercontribs'];

        $commentsToRetrieved = [];
        foreach($comments as $comment){
            if(!isset($commentsToRetrieved[$comment['pageid']])) {
                $commentsToRetrieved[$comment['pageid']] = $comment;
            }
        }

        foreach($commentsToRetrieved as $pageId => $commentToRetrieved){
            $response = $this->httpClient->get(config('wiki.api_uri').$this->commentEndPoint.$pageId);
            $content = json_decode($response->getBody()->getContents(), true);
            $commentsToRetrieved[$pageId] = array_merge($commentsToRetrieved[$pageId], $content['csquerycomment']);

            $realPageId = $content['csquerycomment']['associatedid'];
            $page = PageModel::where('page_id', $realPageId)->first();
            $commentsToRetrieved[$pageId]['picture'] = $page['picture'];
            $commentsToRetrieved[$pageId]['real_page_id'] = $realPageId;
        }

        Cache::put("comments_".$userId, json_encode($commentsToRetrieved), 86400);
        return $commentsToRetrieved;
    }
}
