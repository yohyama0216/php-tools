<?php

class Numbers3 {
    private $sourceFile = "../pastResult/numbers3-past-result.json";
    private $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents($this->sourceFile),true);
    }

    public function getNumbersByRound($round)
    {
        return $this->data[$round];
    }

    public function getRoundListByNumbers($numbers)
    {
        $result = [];
        foreach($this->data as $key => $item) {
            if ($item['numbers'] == $numbers) {
                $result[] = $key;
            }
        }
        return $result;
    }

    /*
     * 連続して同じ数字が出た回を表示する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function getConsecutiveHitNumbers($times)
    {
        $result = [];
        foreach($this->data as $key => $item) {
            if ((int)$key+ $times >= count($this->data)) {
                break;
            }

            $array = [];
            for($i=0;$i<$times;$i++){
                $array[] = $this->data[(int)$key+$i]['numbers'];
            }

            if (count(array_unique($array)) == 1) {
                $result[] = $key;
            } else {
                continue ;
            }
        }
        return $result;
    }
}

$numbers3 = new Numbers3();
//$numbers3->getConsecutiveHitNumbers(2);
var_dump($numbers3->getRoundListByNumbers(111));