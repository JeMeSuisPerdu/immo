<?php

// src/Util/StringUtil.php

namespace App\Util;

class StringUtil
{
    public static function toCamelCase(string $input): string
    {
        $input = strtolower($input);
        $words = explode(' ', $input);
        $camelCase = array_shift($words);

        foreach ($words as $word) {
            $camelCase .= ucfirst($word);
        }

        return $camelCase;
    }
}
