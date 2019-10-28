<?php
namespace App\WebHooks;
/**
 * Class BaseHook - Общий абстрактный класс, для всех веб хуков
 */

abstract class BaseHook
{
    protected $secretKey = null;

    /**
     * Метод проверяет секретный ключ
     * @param string $key - секретный ключ для индетификации запроса
     * @return bool - подтвержден ли
     */
    protected function checkSecretKey(string $key) {
        if($this->secretKey === null) {
            return true;
        }
        return false;
    }

    abstract function processedHook();
}