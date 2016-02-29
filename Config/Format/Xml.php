<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;
use Tale\Dom\Text;
use Tale\Dom\Element;
use Exception;

class Xml implements FormatInterface
{

    public function load($path)
    {

        if (!class_exists('Tale\\Dom\\Parser'))
            throw new Exception(
                "Failed to load XML config: Please install the ".
                "`talesoft/tale-dom` package"
            );

        return $this->_getOptionsFromElement(Element::fromFile($path));
    }

    private function _getOptionsFromElement(Element $element)
    {

        if (!$element->hasChildren())
            return true;

        $result = [];
        foreach ($element as $child) {

            if ($child instanceof Text)
                return $child->getText();

            if ($child instanceof Element)
                $result[$child->getName()] = $this->_getOptionsFromElement($child);
        }

        return $result;
    }
}