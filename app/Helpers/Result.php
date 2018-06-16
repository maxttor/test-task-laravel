<?php

namespace App\Helpers;

class Result
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    public static function success($msg = null, $params = [])
    {
        return self::result(self::STATUS_SUCCESS, $msg, $params);
    }

    public static function error($msg = null, $params = [])
    {
        return self::result(self::STATUS_ERROR, $msg, $params);
    }

    private static function result($status, $msg, $params = [])
    {
        $data = ['status' => $status];
        if ($msg) {
            $data['msg'] = $msg;
        }

        return array_merge($data, $params);
    }
}