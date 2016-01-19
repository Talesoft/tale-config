<?php

namespace Tale;

interface ConfigurableInterface
{

    public function getOptions();
    public function getOption($name, $default = null);
    public function setOptions(array $options, $recursive = false, $reverse = false);
    public function setOption($key, $value);
}