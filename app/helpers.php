<?php

if (!function_exists('configs')) {
    function configs(String $name, $default = null)
    {
        $config = \App\Models\Config::where("name", $name)->first();
        if ($config)
            return $config->value;
        return $default;
    }
}

if (!function_exists('fila')) {
    function fila($loc, $lastmod, $changefreq, $priority) {
        echo "  <url>\n";
        echo "    <loc>".$loc."</loc>\n";
        echo "    <lastmod>".$lastmod."</lastmod>\n";
        echo "    <changefreq>".$changefreq."</changefreq>\n";
        echo "    <priority>".$priority."</priority>\n";
        echo "  </url>\n";
    }
}

if (!function_exists('totalPriceProducts')) {

    function totalPriceProducts($data) {

        $total = collect($data)->map(function($item) {
            if (!isset($item["price"]) || !isset($item["quantity"]))
                return 0;
            return $item["price"] * ((int) $item["quantity"]);
        })->sum();
        return $total;

    }

}

if (!function_exists('deleteFile')) {

    function deleteFile($dirFile) {

        if (file_exists(public_path().$dirFile)) {

            unlink(public_path().$dirFile);

        }

    }

}

if (!function_exists('createJsonFile')) {

    function createJsonFile($dirFile, $data) {

        file_put_contents(public_path().$dirFile, json_encode($data, JSON_UNESCAPED_UNICODE));

    }

}

if (!function_exists('readJsonFile')) {

    function readJsonFile($dirFile) {

        $data = null;
        if (file_exists(public_path().$dirFile)) {
            $data = json_decode(file_get_contents(public_path().$dirFile), true);
        }
        return $data;

    }

}

if (!function_exists('responseReturn')) {
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