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
        foreach ($errors as $error) {
            $result['errors'][] = $error;
        }

        $result['message'] = self::_getStatusCodeMessage($code);
        $result['message'] = self::_getStatusCodeMessage($code);

        Yii::$app->response->statusCode = $code;
        Yii::$app->response->format = Response::FORMAT_JSON;

        echo json_encode($result);
        Yii::$app->end();
    }

    /**
     *
     */
    public static function successResponse($message = '', $data = []) {
        $result = [];
        $result['data'] = $data;
        $result['message'] = $message;

        Yii::$app->response->statusCode = 200;
        Yii::$app->response->format = Response::FORMAT_JSON;

        echo json_encode($result);
        Yii::$app->end();
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
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}