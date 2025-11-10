<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\LocalesConfig;
use App\Src\ForumApiClient;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Forum\ForumUserProvisioner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class GetLastUserComments
{
    public function __construct(
        private UserRepository $userRepository,
        private ForumUserProvisioner $forumUserProvisioner,
    ){}

    public function get(string $userId)
    {
        $comments = Cache::get("comments_".$userId);
        if (isset($comments)) {
            return json_decode($comments, true);
        }

        $commentsToRetrieved = [];

        $user = $this->userRepository->getById($userId);

        $localeConfig = LocalesConfig::query()->where('code', $user->defaultLocale())->first();
        $forumURL = $localeConfig->forum_api_url;

        $discourseUsername = $this->forumUserProvisioner->getUserDiscourseUsernameFromUUID($userId, $localeConfig->code);

        if (!$discourseUsername) {
            return [];
        }
        
        $client = new ForumApiClient($forumURL, $localeConfig->forum_api_key);
        $content = $client->getUserByUsername($discourseUsername);

        if (!empty($content['posts'])) {
            $comments = $content['posts'];

            $topicsTitleById = [];
            $topics = $content['topics'];
            foreach ($topics as $aTopic) {
                $topicsTitleById[$aTopic['id']] = $aTopic['title'];
            }

            foreach ($comments as $aPost) {
                $commentsToRetrieved[$aPost['created_at']] = [
                    'html' => $aPost['blurb'],
                    'title' => $topicsTitleById[$aPost['topic_id']],
                    'url' => $forumURL . '/t/'.$aPost['topic_id'].'/'.$aPost['post_number'],
                    'date' => (new Carbon($aPost['created_at']))->translatedFormat('l j F Y - h:i')
                ];
            }

            // sort the comments
            ksort($commentsToRetrieved);
            $commentsToRetrieved = array_reverse($commentsToRetrieved);
        }

        Cache::put("comments_".$userId, json_encode($commentsToRetrieved), 86400);
        return $commentsToRetrieved;
    }
}
