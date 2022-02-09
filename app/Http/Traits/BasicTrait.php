<?php

namespace App\Http\Traits;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

trait BasicTrait {

    public static function responseElement( $elements, $httpCode = 202, $message = 'OK' ) {

        $error = $httpCode >= 300;
        return response(
            array(
                'error'     => $error,
                'status'    => $httpCode,
                'message'   => $message,
                'elements'  => $elements
            ),
            $httpCode
        );

    }
    public static function checkAttributes( $elements ) {

        $model = new self;
        $attributes = $model->getFillable();
        $auxAttributes = array_map( function($e) use ($attributes) {

            return !in_array($e, $attributes);

        }, $elements);
        if (array_sum($auxAttributes) > 0) {

            throw new ValidationException(
                response(
                    array(
                        'error'     => true,
                        'status'    => 400,
                        'message'   => 'Se recibió atributos de más',
                        'elements'  => $elements
                    ),
                    400
                )
            );

        }
        return true;

    }

}