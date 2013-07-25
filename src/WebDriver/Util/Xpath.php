<?php

namespace WebDriver\Util;

class Xpath
{
    static public function quote($string)
    {
        if (false === strpos($string, '"')) {
            return '"'.$string.'"';
        }

        if (false === strpos($string, "'")) {
            return "'".$string."'";
        }

        $exp = explode('\'', $string);

        return 'concat('.implode(', ', array_map(function ($val) {
            return Xpath::quote($val);
        }, $exp)).')';
    }
}
