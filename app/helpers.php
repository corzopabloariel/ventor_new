<?php

if (! function_exists('config')) {
    function config(String $name)
    {
        $config = \App\Models\Config::where("name", $name)->first();
        if ($config)
            return $config->value;
        return null;
    }
}