<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;
use Symfony\Component\Yaml\Yaml as Parser;
use Exception;

class Yaml implements FormatInterface
{

    public static function getExtensions()
    {

        return ['.yaml', '.yml'];
    }

    public static function load($path)
    {

        if (!class_exists('Symfony\\Component\\Yaml\\Yaml'))
            throw new Exception(
                "Failed to load YAML config: Please install the ".
                "`symfony/yaml` package"
            );

        ;
        return Parser::parse(file_get_contents($path));
    }
}