<?php
declare(strict_types=1);


namespace duodai\worman\interfaces;

interface DecoderInterface
{
    public function decode(string $data):array;
}