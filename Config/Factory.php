<?php

namespace Tale\Config;

use Tale\Config\Format\Ini;
use Tale\Config\Format\Json;
use Tale\Config\Format\Php;
use Tale\Config\Format\Xml;
use Tale\Config\Format\Yaml;

/**
 * Class Factory
 *
 * @package Tale\Config
 *
 * @method FormatInterface[] getAliases()
 * @method FormatInterface resolveClassName($className)
 */
class Factory extends \Tale\Factory
{

    private static $_defaultAliases = [
        'ini' => Ini::class,
        'json' => Json::class,
        'php' => Php::class,
        'xml' => Xml::class,
        'yaml' => Yaml::class
    ];

    public function __construct(array $aliases = null)
    {
        parent::__construct(FormatInterface::class, array_replace(
            self::$_defaultAliases,
            $aliases ?: []
        ));
    }

    public static function registerDefaultAlias($alias, $className)
    {

        self::$_defaultAliases[$alias] = $className;
    }
}