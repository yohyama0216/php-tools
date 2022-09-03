<?php

namespace Test\Search;

class Search {
    private $NumbersPastData = [];
    private $searchResult = [];

    public function __construct($data) {
        $this->NumbersPastData = $data;
    }

    /*
     *  数字の出現回数
     *  @param $order 順序
     *  @param $limit 表示件数
     */
    public function searchAllNumbers()
    {
        $this->countNumbers($this->NumbersPastData);
    }

    private function countNumbers($data)
    {
        $result = [];
        foreach ($data as $numbers) {
            $key = '[' . implode($numbers->getNumbers()) . ']';
            if (!array_key_exists($key, $result)) {
                $result[$key] = 1;
            } else {
                $result[$key] += 1;
            }
        }
        $this->searchResult = $result;
    }

    /*
     * 全桁とも同じ数字が出た回を取得する。
     */
    public function searchSameDigitNumbers()
    {
        $result = [];
        foreach($this->NumbersPastData as $round => $item) {
            $numbersArray = $item->getNumbers();
            if (count(array_unique($numbersArray)) == 1) {
                $numbersString = $item->toString();
                $result[$numbersString] = $round;
            } else {
                continue ;
            }
        }
        $this->searchResult = $result;
    }


    //public function displayAllResultNumbersMiniCount($order = 'desc', $limit = null) {

    public function getRoundListByNumbers($numbers) {
        $result = [];
        foreach ($this->NumbersPastData as $key => $item) {
            if ($item['numbers'] == $numbers) {
                $result[] = $key;
            }
        }
        return $result;
    }

    // 途中？
    public function getFuushaGroup()
    {
        $num100array = [
            0 => 'j',
            1 => 'a',
            2 => 'b',
            3 => 'c',
            4 => 'd',
            5 => 'e',
            6 => 'f',
            7 => 'g',
            8 => 'h',
            9 => 'i',
            ];
        $num10array = [
            0 => 'j',
            7 => 'a',
            4 => 'b',
            1 => 'c',
            8 => 'd',
            5 => 'e',
            2 => 'f',
            9 => 'g',
            6 => 'h',
            3 => 'i',
            ];
        $num1array = [    0 => 'j',
        9 => 'a',
        8 => 'b',
        7 => 'c',
        6 => 'd',
        5 => 'e',
        4 => 'f',
        3 => 'g',
        2 => 'h',
        1 => 'i',
        ];   
        
        $result = [];
        foreach($hit as $numbers) {
            $num100 = $num100array[$numbers[0]];
            $num10 = $num10array[$numbers[1]];
            $num1 = $num1array[$numbers[2]];
            $result[] = [$num100,$num10,$num1];
            echo "$num100,$num10,$num1".PHP_EOL;
        }
    }

    public function countNum10AndNum1Pair() {
        $result = [
            [],[],[],[],[],
            [],[],[],[],[]
        ];
        foreach($this->hitRawNumbersList as $numbers) {
            $mainKey = $numbers['num10'];
            $subKey = $numbers['num10']."-".$numbers['num1'];
            if (array_key_exists($subKey, $result[$mainKey]) == false) {
                $result[$mainKey][$subKey] = 1;
            } else {
                $result[$mainKey][$subKey] += 1;
            }
        }
        ksort($result);
        
        var_dump($result);
        return $result;
    }




    public function displayResult($order = 'desc', $limit=null)
    {
        $result = $this->searchResult;
        if ($order == 'desc') {
            asort($result);
        } else {
            arsort($result);
        }

        if (is_int($limit)) {
            $result = array_chunk($result, $limit, true)[0];
        }
        foreach ($result as $key => $item) {
            echo "$key が $item 回" . PHP_EOL;
        }
    }
}