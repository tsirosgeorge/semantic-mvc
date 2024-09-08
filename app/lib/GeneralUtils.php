<?php

namespace App\lib;

class GeneralUtils
{
    public static function getInitials($string)
    {
        return substr($string, 0, 2);;
    }
}
