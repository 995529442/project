<?php
/**
 * User: huangyugui
 * Date: 16/5/4 13:45
 */

namespace App\Librarys;

date_default_timezone_set('PRC');

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class BLogger
{

    private static $logError = 'error';

    private static $logInfo = 'info';

    private static $loggers = array();

    private static function setLog($type, $file, $day)
    {
        if (empty(self::$loggers[$type])) {
            self::$loggers[$type . $file] = new Logger($type);
            if ($day == true) {
                self::$loggers[$type . $file]->pushHandler((new StreamHandler(storage_path() . '/logs/' . $file . '-' . date('Y-m-d') . '.log'))->setFormatter(new LineFormatter(null, null, true, true)));
            } else {
                self::$loggers[$type . $file]->pushHandler((new StreamHandler(storage_path() . '/logs/' . $file . '-' . date('Y-m-d') . '.log'))->setFormatter(new LineFormatter(null, null, true, true)));
            }
        }
        $log = self::$loggers[$type . $file];
        return $log;
    }

    public static function info($message, $file = 'local', $day = true)
    {
        if (is_array($message)) {
            $message = print_r($message, true);
        }
        self::setLog(self::$logInfo, $file, $day)->info($message);
    }

    public static function error($message, $file = 'local', $day = true)
    {
        if (is_array($message)) {
            $message = print_r($message, true);
        }
        self::setLog(self::$logError, $file, $day)->info($message);
    }
}