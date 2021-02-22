<?php

namespace App\Support;

use GuzzleHttp\Utils;

/**
 * Encode and decode JSON
 * @package App\Support
 */
class Json
{
    /**
     * Wrapper for \GuzzleHttp\Utils::jsonDecode
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return array|bool|float|int|object|string|null
     *
     * @see \GuzzleHttp\Utils::jsonDecode()
     */
    public static function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        return Utils::jsonDecode($json, $assoc, $depth, $options);
    }

    /**
     * Wrapper for \GuzzleHttp\Utils::jsonEncode
     * @param $value
     * @param int $options
     * @param int $depth
     * @return string
     *
     * @see \GuzzleHttp\Utils::jsonEncode()
     */
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        return Utils::jsonEncode($value, $options, $depth);
    }
}
