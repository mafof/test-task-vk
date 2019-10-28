<?php
namespace App\DB;
/**
 * Class BaseDB - Обертка над PDO, для более удобного управления БД
 */

use PDO;
class BaseDB extends PDO
{
    public function __construct()
    {
        // code...
    }
}