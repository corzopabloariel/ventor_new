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

if (! function_exists('fila')) {
    function fila($loc, $lastmod, $changefreq, $priority) {
        echo "  <url>\n";
        echo "    <loc>".$loc."</loc>\n";
        echo "    <lastmod>".$lastmod."</lastmod>\n";
        echo "    <changefreq>".$changefreq."</changefreq>\n";
        echo "    <priority>".$priority."</priority>\n";
        echo "  </url>\n";
    }
}