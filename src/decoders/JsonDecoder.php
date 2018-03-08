<?php
declare(strict_types=1);

namespace duodai\worman\decoders;

use duodai\worman\interfaces\DecoderInterface;

class JsonDecoder implements DecoderInterface
{
    public function decode(string $data): array
    {
        $decoded = json_decode($data, true);
        if(JSON_ERROR_NONE !== json_last_error()){
            throw new \Exception(__METHOD__ . ' error: config file contains invalid json' . json_last_error_msg());
        }
        return $decoded;
    }
}