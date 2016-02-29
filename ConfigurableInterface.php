<?php

namespace Tale;

interface ConfigurableInterface
{

    public function getOptions();
    public function setOptions(array $options, $recursive = false, $reverse = false);
    public function getOption($name, $default = null);
    public function setOption($key, $value);
}