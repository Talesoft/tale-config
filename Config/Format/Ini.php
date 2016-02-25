<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;

class Ini implements FormatInterface
{

    public static function getExtensions()
    {

        return ['.ini'];
    }

    public static function load($path)
    {

        var_dump(self::_getOptionsFromIniArray(parse_ini_file($path, true)));
        return self::_getOptionsFromIniArray(parse_ini_file($path, true));
    }

    private static function _getOptionsFromIniArray(array $array)
    {

        $result = [];
        foreach ($array as $key => $value) {

            if ($key === '') {

                $result = array_replace_recursive(
                    $result,
                    self::_getOptionsFromIniArray($value)
                );
                continue;
            }

            $target = &$result;

            if (strstr($key, '.')) {

                $parts = explode('.', $key);
                $len = count($parts);
                for ($i = 0; $i < $len - 1; $i++) {

                    $subKey = $parts[$i];

                    if (!isset($target[$subKey]))
                        $target[$subKey] = [];
                    else if (!is_array($target[$subKey]))
                        $target[$subKey] = ['value' => $target[$subKey]];

                    $target = &$target[$subKey];
                }

                $key = $parts[$len - 1];
            }

            if (is_array($value)) {

                $target[$key] = self::_getOptionsFromIniArray($value);
                continue;
            }

            $target[$key] = $value;
        }

        return $result;
    }
}