<?php

use Hashids\Hashids;

function generateQr($data, $type = 'E')
{
    $hashids = new Hashids('', 40);

    if ($type === 'E') {
        return $hashids->encode($data);
    } elseif ($type === 'D') {
        return $hashids->decode($data);
    }

    return null;
}
