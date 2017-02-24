<?php

namespace App\Libraries;

class Response{
    public static function formatJson($ec = 200, $em = '', $data = []){
        if (empty($data)) {
            $data = (object)array();
        }

        $return = array(
            'ec' => (int)$ec,
            'em' => $em,
            'data' => $data,
        );

        return $return;
    }
}