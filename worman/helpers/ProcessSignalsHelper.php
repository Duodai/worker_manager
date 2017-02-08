<?php


namespace app\worman\helpers;


/**
 * Class ProcessSignalsHelper
 *
 * Methods to work with pcntl_signals
 * (Originally made for TQP)
 *
 * @author Michael Janus <abyssal@mail.ru>
 *
 * @package app\helpers
 */
class ProcessSignalsHelper
{

    /**
     *  Dispatch pending signals
     */
    public static function checkSignals()
    {
        pcntl_signal_dispatch();
    }

    /**
     * Add signals to blacklist. This signals will not be dispatched.
     *
     * @param array $signals Example: [SIGHUP, SIGKILL]
     */
    public static function blockSignals(array $signals)
    {
        pcntl_sigprocmask(SIG_BLOCK, $signals);
    }

    /**
     * Remove signals from blacklist
     *
     * @param array $signals
     */
    public static function unblockSignals(array $signals)
    {
        pcntl_sigprocmask(SIG_UNBLOCK, $signals);
    }

    /**
     * Replace current signals blacklist
     * (This method clears current list of blocked signals before adding new values)
     *
     * @param array $signals
     */
    public static function replaceBlockedSignalsList(array $signals)
    {
        pcntl_sigprocmask(SIG_SETMASK, $signals);
    }

    /**
     * Add signal handler
     *
     * @param int $signal
     * @param string|Object $object
     * @param string|int|callable $handler
     * @return bool
     * @throws \Exception
     */
    public static function addSignalListener($signal, $handler, $object = null)
    {
        if (!($signal = (int)$signal) || $signal <= 0) {
            throw new \Exception('Wrong argument supplied to ' . __CLASS__ . '::' . __FUNCTION__ . '():
             $signal must be in unsigned integer format');
        }

        if (!empty($object)) {
            if (is_string($object) && !class_exists($object)) {
                throw new \Exception('Wrong argument supplied to ' . __CLASS__ . '::' . __FUNCTION__ . '():
                 $object must be an existing class name');
            }
            if (!method_exists($object, $handler)) {
                throw new \Exception('Wrong argument supplied to ' . __CLASS__ . '::' . __FUNCTION__ . '():
                 $handler must be an existing method of class, supplied in $object');
            }
            pcntl_signal($signal, [$object, $handler]);
        } else {
            if (!is_callable($handler) && !in_array($handler, [SIG_IGN, SIG_DFL])) {
                throw new \Exception('Wrong argument supplied to ' . __CLASS__ . '::' . __FUNCTION__ . '():
                 $handler must be an existing method of class, supplied in $object');
            }
            pcntl_signal($signal, $handler);
        }
        return true;
    }

    /**
     * Send signal to process
     *
     * @param int $PID Process ID
     * @param int $signal
     *
     * @return boolean
     */
    public static function sendSignal($PID, $signal)
    {
        return posix_kill($PID, $signal);

    }
}
