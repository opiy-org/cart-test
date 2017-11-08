<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 03.11.2017
 * Time: 21:28
 */

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Validator;

class JResponseService
{

    /**
     *  Return json with data and 200 http code by default
     *
     * @param $data
     * @param int $code
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    public static function data($data, int $code = 200, array $headers = [], int $options = JSON_PRETTY_PRINT): JsonResponse
    {
        return Response::json([
            'data' => $data,
//            'session'=>\Illuminate\Support\Facades\Session::all(),
        ], $code, $headers, $options);
    }


    /**
     *  Return json with error and 404 http code by default
     *
     * @param $error
     * @param int $code
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    public static function error($error, int $code = 404, array $headers = [], int $options = JSON_PRETTY_PRINT): JsonResponse
    {
        return Response::json([
            'error' => $error
        ], $code, $headers, $options);
    }


    /**
     *  Render validator errors
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    public static function validation_errors(Validator $validator): JsonResponse
    {
        $params = [];
        $messages = $validator->messages()->toArray();
        $rules = $validator->getRules();

        foreach ($messages as $attr => $attr_errors) {
            foreach ($attr_errors as $attr_error) {

                $code = implode(',',array_flatten($rules));
                foreach ($rules[$attr] as $rule) {
                    $ruletmp=str_replace('min:','',$rule);
                    $ruletmp=str_replace('max:','',$ruletmp);
                    if (strpos($attr_error, $ruletmp) != false) {
                        $code = $rule;
                    }
                }

                $params[] = [
                    'code' => $code,
                    'message' => $attr_error,
                    'name' => $attr,
                ];
            }
        }


        $error = [
            'type' => 'invalid_param_error',
            'message' => 'Invalid data parameters',
            'params' => $params,
        ];

        return self::error($error, 400);

    }

}