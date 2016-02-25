<?php

namespace Tale;


use Tale\Config\Format\Ini;
use Tale\Config\Format\Json;
use Tale\Config\Format\Php;
use Symfony\Component\Yaml\Yaml;
use Tale\Config\FormatInterface;
use Tale\Config\Format\Xml;

final class Config
{

    /**
     * @var FormatInterface[]
     */
    private static $_formats = [
        'ini' => Ini::class,
        'json' => Json::class,
        'php' => Php::class,
        'xml' => Xml::class,
        //'yaml' => Yaml::class
    ];

    public static function load($path, $format = null)
    {

        if (!$format) {

            $ext = pathinfo($path, PATHINFO_EXTENSION);
            foreach (self::$_formats as $name => $className) {

                if (in_array(".$ext", $className::getExtensions())) {

                    $format = $name;
                    break;
                }
            }
        }



        if (!$format || !isset(self::$_formats[$format]))
            throw new \RuntimeException(
                "Failed to load config file $path: Config format not ".
                "recognized"
            );

        $className = self::$_formats[$format];
        return $className::load($path);
    }
}