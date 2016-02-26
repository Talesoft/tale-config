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
        'ini' => Ini::class,
        'json' => Json::class,
        'php' => Php::class,
        'xml' => Xml::class,
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

    public static function load($path, FormatInterface $format = null)
    {

        if (!$format)
            $format = self::getFormatForPath($path);

        return $format->load($path);
    }
}