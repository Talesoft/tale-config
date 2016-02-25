<?php

namespace Tale;

use Tale\Config\Factory;
use Tale\Config\FormatInterface;

final class Config
{

    public static function load($path, $format = null, array $aliases = null)
    {

        $formatFactory = new Factory($aliases);

        if (!$format) {

            $ext = pathinfo($path, PATHINFO_EXTENSION);
            foreach ($formatFactory->getAliases() as $alias => $className) {

                if (in_array(".$ext", $className::getExtensions())) {

                    $format = $alias;
                    break;
                }
            }
        }

        if (!$format)
            throw new \RuntimeException(
                "Failed to load config file $path: ".
                "No valid format handler found"
            );

        if (!($className = $formatFactory->resolveClassName($format)))
            throw new FactoryException(
                "Failed to resolve class name $format. ".
                "Make sure it implements ".FormatInterface::class
            );

        return $className::load($path);
    }
}