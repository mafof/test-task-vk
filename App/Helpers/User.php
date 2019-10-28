<?php
namespace App\Helpers;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class User implements \ConfigApp
{
    private $vkObj;

    public function __construct()
    {
        $this->vkObj = new VKApiClient();
    }

    public function sendMessage(string $user_id, string $message) : void
    {
        try {
            $this->vkObj->messages()->send(static::VK_TOKEN, [
                'user_id' => $user_id,
                'message' => $message,
                'random_id' => rand(0, 1000)
            ]);
        } catch (VKApiException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        } catch (VKClientException $e) {
            file_put_contents("error_logs.txt", $e->getErrorMessage() . PHP_EOL, FILE_APPEND);
            die();
        }
    }
}