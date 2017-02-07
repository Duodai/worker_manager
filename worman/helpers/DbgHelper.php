<?php

namespace app\worman\helpers;

use Webmozart\Assert\Assert;

class DbgHelper
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

}
