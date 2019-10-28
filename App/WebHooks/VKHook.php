<?php
namespace App\WebHooks;

use App\Helpers\Group;
use App\Helpers\Image;
use App\Helpers\User;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;

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
        $user = new User();
        $arrMsg = explode('vk.com/', $object['body']);
        if(count($arrMsg) == 2) {
            $group = new Group();
            $userData = $group->getUserProfile($arrMsg[1]);

            if($userData !== null) {
                $fullName = $userData[0]['first_name'] . ' ' . $userData[0]['last_name'];
                $cover = new Image();
                $cover->updateCoverPhoto($userData[0]['photo_200'], $fullName);
                $group->setCoverPhoto();
                $user->sendMessage($object['user_id'], "Обложка успешно обновлена");
            } else {
                $user->sendMessage($object['user_id'], "Пользователь не найден");
            }
        } else {
            $user->sendMessage($object['user_id'], "Не правильный формат ссылки");
        }
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