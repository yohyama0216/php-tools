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
        $this->searchResult = $this->NumbersPastData;
    }

    /*
     * 全桁とも同じ数字が出た回数
     */
    public function searchSameDigitNumbers()
    {
        $result = array_filter($this->NumbersPastData,function($v) {
            return $v->isSameDigit();
        },ARRAY_FILTER_USE_BOTH);
        $this->searchResult = $result;
    }

    /**
     * 前回と同じ数字が出るケース
     */


    /**
     * 階段数字が出るケース
     */
    public function searchStepNumbers()
    {
        $result = array_filter($this->NumbersPastData,function($v) {
            return $v->isStep();
        },ARRAY_FILTER_USE_BOTH);
        $this->searchResult = $result;
    }

    /**
     * 鏡数字が出るケース
     */
    public function searchMirrorNumbers()
    {
        $result = array_filter($this->NumbersPastData,function($v) {
            return $v->isMirror();
        },ARRAY_FILTER_USE_BOTH);
        $this->searchResult = $result;
    }

    /**
     * 2回連続で同じ数字が出るケース
     */
    public function searchSameNumberTimes()
    {
        $result = array_filter($this->NumbersPastData,function($v,$k) {
            echo $k.":".$v->getNumbersString();
            if ($k > 1){
                $currentNumbers = $v->getNumbers();
                $prevNumbers = $this->NumbersPastData[$k-1]->getNumbers();
                $condition = $currentNumbers == $prevNumbers;
                if ($condition) {
                   echo "-".$v->getRound();
                }
                echo PHP_EOL;
                return $condition ;
            }

        },ARRAY_FILTER_USE_BOTH);
        $this->searchResult = $result;
    }    
    /**
     * 過去5回に同じ数字が出るケース
     */

    /**
     * 
     */

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
        return $result;
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
        $result = $this->countNumbers($this->searchResult);
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