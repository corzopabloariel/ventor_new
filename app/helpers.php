<?php

if (! function_exists('element_form')) {
    function element_form($type, $name, $id, $class = 'form-control', $label = false, $placeholder = NULL)
    {
        $html = "";
        $placeholder = empty($placeholder) ? "placeholder=$name" : "placeholder=$placeholder";
        $class = "class=$class";
        $name = "name=$name";
        $id = "id=$id";

        switch($type)
        {
            case "text":
                $element = "<input $name $id $class type=text $placeholder />";
            break;
            case "image":
                $element = "<div class=\"form-image\">";
                    $element .= "<div class=\"row\">";
                        $element .= "<div class=\"col-12 col-md\">";
                            $element .= "<label class=\"form-image__label\">";
                                $element .= "<span>Seleccione imagen</span>";
                                $element .= "<input $name $id class=\"image-select\" type=file />";
                            $element .= "</label>";
                        $element .= "</div>";
                        $element .= "<div class=\"col-12 col-md\">";
                            $element .= "<img src class=\"w-100\" onerror=\"this.src='" . asset('images/no-img.png') . "'\" />";
                        $element .= "</div>";
                    $element .= "</div>";
                $element .= "</div>";
            break;
        }
        $html .= "<div class=\"form-group\">";
            if ($label)
            {
                $html .= "<label " . str_replace("id=", "for=", $id) . ">" . str_replace("placeholder=", "", $placeholder) . "</label>";
            }
            $html .= $element;
        $html .= "</div>";
        return $html;
    }
}