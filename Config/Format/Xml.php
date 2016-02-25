<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;
use Tale\Dom\Text;
use Tale\Dom\Element;

class Xml implements FormatInterface
{

    public static function getExtensions()
    {

        return ['.xml', '.config'];
    }

    public static function load($path)
    {

        if (!class_exists('Tale\\Dom\\Parser'))
            throw new Exception(
                "Failed to load XML config: Please install the ".
                "`talesoft/tale-dom` package"
            );

        return self::_getOptionsFromElement(Element::fromFile($path));
    }

    private static function _getOptionsFromElement(Element $element)
    {

        if (!$element->hasChildren())
            return true;

        $result = [];
        foreach ($element as $child) {

            if ($child instanceof Text)
                return $child->getText();

            if ($child instanceof Element)
                $result[$child->getName()] = self::_getOptionsFromElement($child);
        }

        return $result;
    }
}