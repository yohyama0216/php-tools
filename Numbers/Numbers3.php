<?php

class Prediction {
    private $NumbersPastData;
    private $StaticsService;

    public function __construct($data) {
        $this->NumbersPastData = new NumbersPastData(3);
    }

    public function predict() {
        $this->predict();
    }
}

class Numbers {
    private $numbersType = "";
    private $round = "";
    private $date = "";
    private $numbers = [];

    public function __construct($numbersType, $round, $date, $numbersString)
    {
        if ($numbersType != strlen($numbersString)) {
            return null;
        }
        $this->numbersType = $numbersType;
        $this->round = $round;
        $this->date = $date;
        $this->numbers = str_split($numbersString);
    }

    public function getNumbersType()
    {
        return $this->numbersType;
    }

    public function getRound()
    {
        return $this->round;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getNumbers()
    {
        return $this->numbers;
    }


}

class NumbersUtil {

    /*
     *  全パターンを出力
     */
    public static function getAllNumbersPattern($numbersType) {
        $result = [];
        $max = str_repeat(9,$numbersType);
        for ($i = 0; $i <= $max; $i++) {
            $result[] = str_pad($i, $numbersType, '0', STR_PAD_LEFT);
        }
        return $result;
    }

    /*
     *  Box当選かどうか
     */
    public static function isBoxHit($numbers1Array, $numbers2Array) {
        sort($numbers1Array);
        sort($numbers2Array);
        return (implode($numbers1Array) == implode($numbers2Array));
    }
}

class NumbersPastData {
    private $sourceFile = "../pastResult/numbers3-past-result.json";
    private $numbersType = "";
    private $data = [];

    public function __construct($numbersType, $start = null, $end = null) {
        $this->numbersType = $numbersType;
        $data = json_decode(file_get_contents($this->sourceFile), true);
        $this->data = $this->createPastData($data, $start, $end);
    }

    public function getData() {
        return $this->data;
    }

    /*
     *  過去データmodel作成
     */
    private function createPastData($data, $start, $end) {
        $result = [];
        if ($start && $end) {
            $data = array_slice($data, $start, $end);
        }
        foreach ($data as $key => $item) {
            $result[] = new Numbers(3, $key,$item['date'],$item['numbers']);
        }
        return $result;
    }

    /*
     * ミニの取得
     */
    public function getAllNumbersMini() {
        if ($this->numbersType !== 3) {
            return [];
        }

        foreach ($this->data as $key => $item) {
            $data[$key]['numbers'] = [
                $item['numbers'][1], $item['numbers'][2]
            ];
        };
        return $data;
    }
}

class StaticsService {
    private $NumbersPastData = [];

    public function __construct($data) {
        $this->NumbersPastData = $data;
    }

    /*
     *  数字の出現回数
     *  @param $order 順序
     *  @param $limit 表示件数
     */
    public function displayAllResultNumbersCount($order = 'desc', $limit = null) {
        $result = [];
        echo __METHOD__ . PHP_EOL;
        foreach ($this->NumbersPastData as $numbers) {
            $key = '[' . implode($numbers->getNumbers()) . ']';
            if (!array_key_exists($key, $result)) {
                $result[$key] = 1;
            } else {
                $result[$key] += 1;
            }
        }
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

    public function getRoundListByNumbers($numbers) {
        $result = [];
        foreach ($this->NumbersPastData as $key => $item) {
            if ($item['numbers'] == $numbers) {
                $result[] = $key;
            }
        }
        return $result;
    }
}

$NumbersPastData = new NumbersPastData(3);
$StaticsService = new StaticsService($NumbersPastData->getData());
$StaticsService->displayAllResultNumbersCount($NumbersPastData->getData(),'a',null);
