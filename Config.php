<?php

namespace Tale;

use Tale\Config\Format\Ini;
use Tale\Config\Format\Json;
use Tale\Config\Format\Php;
use Tale\Config\Format\Xml;
use Tale\Config\Format\Yaml;
use Tale\Config\FormatInterface;

final class Config
{

    private static $_formats = [
        'ini'  => Ini::class,
        'json' => Json::class,
        'php'  => Php::class,
        'xml'  => Xml::class,
        'yaml' => Yaml::class
    ];
    private static $_formatFactory = null;

    public static function createFormatFactory(array $formats = null)
    {

        return new Factory(FormatInterface::class, $formats);
    }

    public static function getFormatFactory()
    {

        if (self::$_formatFactory === null)
            self::$_formatFactory = self::createFormatFactory(
                self::$_formats
            );

        return self::$_formatFactory;
    }

    public static function getFormatForPath($path)
    {

        $factory = self::getFormatFactory();
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        /** @var FormatInterface[] $aliases */
        $aliases = $factory->getAliases();
        $formatAlias = null;
        foreach ($aliases as $alias => $className) {

            if (in_array(".$ext", $className::getExtensions())) {

                $formatAlias = $alias;
                break;
            }
        }

        if (!$formatAlias)
            throw new \RuntimeException(
                "Failed to get format for $path: ".
                "No valid format handler found"
            );

        return $factory->create($formatAlias);
    }

    /**
     * Resolves a key.subKey.subSubKey-style string to a deep array value
     *
     * The function accesses multi-dimensional keys with a delimiter given (Default: Dot (.))
     *
     * @protip If you want to throw an exception if no key is found, pass the exception as the default value
     *         and throw it, if the result is an Exception-type
     *
     * @param string      $key          The input key to operate on
     * @param array       $source       The array to search values in
     * @param mixed       $defaultValue The default value if no key is found
     * @param string|null $delimiter    The delimeter to access dimensions (Default: Dot (.))
     *
     * @return array|null The found value or the default value, if none found (Default: null)
     */
    public static function resolve($key, array $source, $defaultValue = null, $delimiter = null)
    {

        $delimiter = $delimiter ?: '.';
        $current = &$source;
        $keys = explode($delimiter, $key);

        foreach ($keys as $key) {

            if (is_numeric($key))
                $key = intval($key);

            if (!isset($current[$key]))
                return $defaultValue;

            $current = &$current[$key];
        }

        return $current;
    }

    /**
     * Interpolates {{var.subVar}}-style based on a source array given
     *
     * Dimensions in the source array are accessed with a passed delimeter (Default: Dot (.))
     *
     * @param string      $string       The input string to operate on
     * @param array       $source       The associative source array
     * @param mixed       $defaultValue The default value for indices that dont exist
     * @param string|null $delimiter    The delimeter for multi-dimension access (Default: Dot (.))
     *
     * @return string The interpolated string with the variables replaced with their values
     */
    public static function interpolate($string, array $source, $defaultValue = null, $delimiter = null)
    {

        $defaultValue = $defaultValue ?: '';

        return preg_replace_callback('/\{\{([^\}]+)\}\}/i', function ($m) use ($source, $defaultValue, $delimiter) {

            $key = $m[1];

            if (defined($key))
                return constant($key);

            return self::resolve($key, $source, $defaultValue, $delimiter);
        }, $string);
    }

    /**
     * Interpolates a multi-dimensional array with another array recursively
     *
     * If no source is given, you get a live interpolation where you can directly interpolate
     * variables that have just been interpolated before
     *
     * This is mostly used for option arrays, e.g. config-files
     *
     * @param array      $array        The array to interpolate (Passed by reference)
     * @param array|null $source       The source array for variables. If none given, the input array is taken
     * @param null       $defaultValue The default value for indices that couldnt be resolved
     * @param string     $delimiter    The delimeter used for multi-dimension access (Default: Dot (.))
     */
    public static function interpolateArray(array &$array, array &$source = null, $defaultValue = null, $delimiter = null)
    {

        if (!$source)
            $source = &$array;

        foreach ($array as $key => &$val) {

            if (is_array($val))
                self::interpolateArray($val, $source, $defaultValue, $delimiter);
            else if (is_string($val))
                $array[$key] = self::interpolate($val, $source, $defaultValue, $delimiter);
        }
    }

    public static function load($path, FormatInterface $format = null)
    {

        if (!$format)
            $format = self::getFormatForPath($path);

        return $format->load($path);
    }
}