<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\web\Response;

class ApiComponent extends Component
{

    /**
     *
     */
    public static function errorResponse($errors = [], $code) {
        $result = [];

        $errors_data = [];
        foreach ($errors as $error) {
            $errors_data[] = $error;
        }

        $result['data'] = $errors_data;
        $result['message'] = self::_getStatusCodeMessage($code);
        $result['code'] = 0;
        $result['status'] = $code;

        Yii::$app->response->statusCode = 400;
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $result;
    }

    /**
     *
     */
    public static function successResponse($message = '', $data = [], $isArray = false) {
        $result = [];
        if($isArray) {
            $result['data'] = $data;
        } else {
            $result['data'][] = $data;
        }
        $result['message'] = $message;
        $result['code'] = 1;
        $result['status'] = 200;

        Yii::$app->response->statusCode = 200;
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $result;
    }

    public static function parseInputData() {
        $json = file_get_contents('php://input');
        $req = json_decode($json, true);

        return $req;
    }

    private static function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',

            1000 => 'Enter required data',
            1001 => 'Username or password is incorrect',
            1002 => 'Item not found',
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}