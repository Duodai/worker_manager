<?php

namespace app\worman\helpers;

use app\worman\exceptions\LauncherException;
use app\worman\interfaces\DaemonInterface;
use Webmozart\Assert\Assert;

class ConsoleHelper
{

    /**
     * Print message
     * @param string $message
     */
    public static function msg($message)
    {
        Assert::string($message, __METHOD__ . ' error: $message must be a string');
        $date = date('Y-m-d H:i:s');
        echo "$date $message" . PHP_EOL;
    }

    public static function unbind(DaemonInterface $daemon)
    {
        // daemonize (unbind from console)
        $pid = pcntl_fork();
        self::msg('process ' . $pid . ' unbound');
        if ($pid === -1) {
            throw new LauncherException(__METHOD__ . ' error: process forking failed');
        } elseif ($pid !== 0) {
            exit;
        } else {
            $daemon->start();
        }
    }

}
