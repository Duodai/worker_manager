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
        if ($pid === -1) {
            throw new LauncherException(__METHOD__ . ' error: process forking failed');
        } elseif ($pid !== 0) {
            self::msg('process ' . $pid . ' unbound');
            exit;
        } else {
            self::redirectOutput();
            $daemon->start();
        }
    }

    protected static function redirectOutput() {
        global $STDIN;
        global $STDOUT;
        global $STDERR;
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        $STDIN = fopen('/dev/null', 'r');
        $STDOUT = fopen(\Yii::getAlias('@runtime').'/logs/application.log', 'wb');
        $STDERR = fopen(\Yii::getAlias('@runtime').'/logs/error.log', 'wb');
    }

}
