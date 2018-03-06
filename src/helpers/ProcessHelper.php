<?php


namespace duodai\worman\helpers;


class ProcessHelper
{
    protected const CHILD_NORMAL_EXIT_SIGNAL = 0;
    protected const CHILD_ERROR_SIGNAL = 1;
    protected const CHILD_RESTART_REQUEST_SIGNAL = 2;

    public static function isRunning(int $pid)
    {
        return (bool) posix_getpgid($pid);
    }

    public static function kill(int $pid)
    {
       ProcessSignalsHelper::sendSignal($pid, SIGKILL);
    }
}