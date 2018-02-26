<?php


namespace app\worman\interfaces;


interface SystemScannerInterface
{

    public function init();

    public function getFreeRam();

    public function getTotalRam();

    public function getFreeCpu();

    public function getTotalCpu();

}
