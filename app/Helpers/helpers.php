<?php

use Hashids\Hashids;

function generateQr($data, $type = 'E', $routeName = 'verifiy.qr')
{
    $hashids = new Hashids('', 40);

    if ($type == 'E') {
        return route($routeName, $hashids->encode($data));
    } elseif ($type == 'D') {
        return $hashids->decode($data);
    } else {
        return null;
    }
}
