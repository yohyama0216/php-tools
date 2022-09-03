<?php

namespace Test\Numbers;

class Winning {
    /*
     *  Box当選かどうか
     */
    public static function isBox(Numbers $Numbers1, Numbers $Numbers2) {
        $numbers1Array = $Numbers1->getNumbers();
        $numbers2Array = $Numbers2->getNumbers();        
        sort($numbers1Array);
        sort($numbers2Array);
        return (implode($numbers1Array) == implode($numbers2Array));
    }

    // 移植
    private function hasHippariNumber($predict_number) {
        $number_array = str_split($predict_number);
        foreach($number_array as $number) {
            if (strpos($this->beforeHitNumber,$number) !== false) {
                return true;
            }
        }
        return false;
    }
}