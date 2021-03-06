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

if (! function_exists('responseReturn')) {
    function responseReturn(Bool $onlyText, String $message, Int $error = 0, Int $codeError = 200, $append = []) {

        if ($onlyText) {

            return $message;

        }

        $data = array_merge([
            'error' => $error,
            'message' => $message
        ], $append);
        return response()->json($data, $codeError);

    }
}

if (! function_exists('clearRow')) {
    /**
     *
     * @param  String $row
     * @return String
     */
    function clearRow(String $row) {

        $row = str_replace("&nbsp;", "", $row);
        $row = preg_replace('/\s+/', ' ', $row);
        $value = html_entity_decode(trim($row));
        return $value === "" ? NULL : $value;

    }

}