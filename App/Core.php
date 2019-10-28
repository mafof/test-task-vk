<?php
namespace App;
/**
 * Class Core - предназначен для обработки запроса
 */

final class Core implements ErrorCode
{
    private $arrPathInfo = [];

    public function __construct()
    {
        $this->getPathInfoArr();
        $this->checkTypeRequest();
    }

    private function getPathInfoArr()
    {
        if(strlen($_SERVER["PATH_INFO"]) > 0) {
            $this->arrPathInfo = explode('/', $_SERVER["PATH_INFO"]);
            array_shift($this->arrPathInfo);
        }
    }

    private function checkTypeRequest()
    {
        if(count($this->arrPathInfo) >= 1) {
            switch ($this->arrPathInfo[0]) {
                case "wh":
                    $this->checkTypeService();
                    break;
                default:
                    $this->showError(self::NOT_FOUND_TYPE_REQUEST, "Не найден тип {$this->arrPathInfo[0]}");
                    break;
            }
        } else {
            $this->showError(self::NOT_VALID_REQUEST, "Не указан тип обращения");
        }
    }

    private function checkTypeService()
    {
        if(count($this->arrPathInfo) >= 2) {
            switch ($this->arrPathInfo[1]) {
                case "vk":
                    $handler = new WebHooks\VKHook();
                    $data = json_decode(file_get_contents('php://input'));
                    $handler->parse($data);
                    break;
                default:
                    $this->showError(self::NOT_FOUND_SERVICE, "Не найден сервис {$this->arrPathInfo[1]}");
                    break;
            }
        } else {
            $this->showError(self::NOT_VALID_REQUEST, "Не указано название сервиса");
        }
    }

    private function showError(int $codeError, string $message)
    {
        echo json_encode(["error" => $codeError, "message" => $message], JSON_UNESCAPED_UNICODE);
    }
}