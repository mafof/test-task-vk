<?php
namespace App\WebHooks;

use App\Helpers\Group;
use App\Helpers\Image;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class VKHook extends VKCallbackApiServerHandler
{

    const SECRET = \ConfigApp::VK_SECRET;
    const GROUP_ID = \ConfigApp::VK_GROUP_ID;
    const CONFIRMATION_TOKEN = \ConfigApp::VK_CONFIRMATION_TOKEN;

    function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === self::SECRET && $group_id === self::GROUP_ID) {
            echo self::CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        file_put_contents('logs.txt', 'MESSAGE NEW =>' . PHP_EOL, FILE_APPEND);
        file_put_contents('logs.txt', $secret . PHP_EOL, FILE_APPEND);
        file_put_contents('logs.txt', var_export($object, true) . PHP_EOL, FILE_APPEND);
        echo 'OK';
    }

    public function groupJoin(int $group_id, ?string $secret, array $object)
    {
        $group = new Group();
        $userData = $group->getUserProfile($object['user_id']);
        $fullName = $userData[0]['first_name'] . ' ' . $userData[0]['last_name'];

        $cover = new Image();
        $cover->updateCoverPhoto($userData[0]['photo_200'], $fullName);
        $group->setCoverPhoto();
        echo 'OK';
    }
}