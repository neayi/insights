<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class GetLastWikiUserComments
{
    private $httpClient;

    public function __construct(UserRepository $userRepository)
    {
        $hostname = config('services.discourse.api.url');
        $this->httpClient = new Client(['base_uri' => $hostname]);

        $this->userRepository = $userRepository;
    }

    public function get(string $userId)
    {
        $comments = Cache::get("comments_".$userId);
        if(isset($comments)){
            return json_decode($comments, true);
        }

        $user = $this->userRepository->getById($userId);

        $response = $this->httpClient->get('search.json?q=order:latest @'.$user->discourse_username());
        $content = json_decode($response->getBody()->getContents(), true);
        $commentsToRetrieved = [];

        if (!empty($content['posts']))
        {
            $comments = $content['posts'];

            $topicsTitleById = array();
            $topics = $content['topics'];
            foreach ($topics as $aTopic)
                $topicsTitleById[$aTopic['id']] = $aTopic['title'];

            $forumURL = config('services.discourse.url');

            foreach ($comments as $aPost)
            {
                $commentsToRetrieved[$aPost['created_at']] = array(
                    'html' => $aPost['blurb'],
                    'title' => $topicsTitleById[$aPost['topic_id']],
                    'url' => $forumURL . '/t/'.$aPost['topic_id'].'/'.$aPost['post_number'],
                    'date' => (new Carbon($aPost['created_at']))->translatedFormat('l j F Y - h:i'));
            }

            // sort the comments
            ksort($commentsToRetrieved);
            $commentsToRetrieved = array_reverse($commentsToRetrieved);
        }

        Cache::put("comments_".$userId, json_encode($commentsToRetrieved), 86400);
        return $commentsToRetrieved;
    }
}
