<?php
namespace App\Helpers;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

/**
 * Class Group - Класс обертка для VK API, для работы с группой
 */

class Group implements \ConfigApp
{
    private $vkObj;

    public function __construct()
    {
        $this->vkObj = new VKApiClient();
    }

    public function getUserProfile(string $user_id) : ?array
    {
        try {
            $user = $this->vkObj->users()->get(static::VK_TOKEN, [
                'user_ids' => [$user_id],
                'fields' => ['photo_200']
            ]);

            if($user[0]['first_name'] === 'DELETED') {
                return null;
            } else {
                return $user;
            }
        } catch (VKApiException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            return null;
        } catch (VKClientException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        }
    }

    public function setCoverPhoto() : void
    {
        try {
            $img = new \Imagick('img/complete.jpg');
            $size = $img->getImageGeometry();

            $resUploadServer = $this->vkObj->photos()->getOwnerCoverPhotoUploadServer(static::VK_TOKEN, [
                'group_id' => static::VK_GROUP_ID,
                'crop_x' => 0,
                'crop_y' => 0,
                'crop_x2' => $size['width'],
                'crop_y2' => $size['height']
            ]);

            $resRemotePhotoData = $this->vkObj->getRequest()->upload($resUploadServer['upload_url'], 'photo', 'img/complete.jpg');

            $this->vkObj->photos()->saveOwnerCoverPhoto(static::VK_TOKEN, [
                'hash' => $resRemotePhotoData['hash'],
                'photo' => $resRemotePhotoData['photo'],
            ]);
        } catch (VKApiException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        } catch (VKClientException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        } catch (\ImagickException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        }
    }
}