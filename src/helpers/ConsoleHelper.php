<?php

namespace app\worman\helpers;

use app\worman\exceptions\LauncherException;
use app\worman\interfaces\DaemonInterface;
use Webmozart\Assert\Assert;

class ConsoleHelper
{

    const DEV_NULL_PATH = '/dev/null';

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

    /**
     * Daemonize (unbind from console)
     *
     * @param DaemonInterface $daemon
     * @param string $stdOutFilePath
     * @param string $stdErrFilePath
     * @throws LauncherException
     */
    public static function unbind(DaemonInterface $daemon, string $stdOutFilePath, string $stdErrFilePath)
    {
        $pid = pcntl_fork();
        if ($pid === -1) {
            throw new LauncherException(__METHOD__ . ' error: process forking failed');
        } elseif ($pid !== 0) {
            self::msg('process ' . $pid . ' unbound');
            exit;
        } else {
            self::redirectOutput($stdOutFilePath, $stdErrFilePath);
            $daemon->start();
        }
    }

    /**
     * Drop STDIN, redirect STDOUT and STDERR
     * @param string $stdOutFilePath
     * @param string $stdErrFilePath
     */
    public static function redirectOutput(string $stdOutFilePath, string $stdErrFilePath)
    {
        global $STDIN;
        global $STDOUT;
        global $STDERR;
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        $STDIN = fopen(self::DEV_NULL_PATH, 'r');
        $STDOUT = fopen($stdOutFilePath, 'wb');
        $STDERR = fopen($stdErrFilePath, 'wb');
    }

}
