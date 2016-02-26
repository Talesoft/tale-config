<?php

namespace Tale\Config;

interface FormatInterface
{

    public function load($path);
    //TODO: public static function save($path, array $options)
    public static function getExtensions();
}