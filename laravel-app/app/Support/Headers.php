<?php

namespace App\Support;

/**
 * Class Headers
 * @package App\Support
 */
class Headers
{
    /**
     * @param array $headers
     * @return array
     */
    public static function process(array $headers)
    {
        return collect($headers)
            ->reject(function ($value) {
                return !is_array($value) || empty($value);
            })
            ->map(function ($item) {
                return $item[0];
            })
            ->toArray();
    }
}
