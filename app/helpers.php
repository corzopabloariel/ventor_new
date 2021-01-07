<?php

if (! function_exists('configs')) {
    function configs(String $name, $default = null)
    {
        $config = \App\Models\Config::where("name", $name)->first();
        if ($config)
            return $config->value;
        return $default;
    }
}