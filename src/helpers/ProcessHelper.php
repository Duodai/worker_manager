<?php


namespace duodai\worman\helpers;


class ProcessHelper
{

    public static function isRunning(int $pid)
    {
        return (bool) posix_getpgid($pid);
    }

    public static function kill(int $pid)
    {
       ProcessSignalsHelper::sendSignal($pid, SIGKILL);
    }
}