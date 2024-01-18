<?php

namespace App\Service;

class Censurator
{
    public function purify(string $string):string
    {
        $blackList = ['macron'];

        foreach ($blackList as $item){
            $response = str_replace($item, '*', $string);
        }

        return $response;
    }
}