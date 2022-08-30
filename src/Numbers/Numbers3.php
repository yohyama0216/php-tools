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
    private $mini = [];

    public function __construct($numbersType, $round, $date, $numbersString)
    {
        if ($numbersType != strlen($numbersString)) {
            return null;
        }
        $this->numbersType = $numbersType;
        $this->round = $round;
        $this->date = $date;
        $this->numbers = $this->createNumbersArray($numbersString);
        
        if ($numbersType == 3) {
            $this->mini = [
                '10digit' => $this->numbers['10digit'], 
                '1digit' => $this->numbers['1digit']
            ];
        }
    }

    /*
     *  数字を分割して、キーに桁をつける
     */
    private function createNumbersArray($numbersString)
    {
        $result = [];
        $arr = str_split($numbersString);
        foreach($arr as $key => $char) {
            $key = "1".str_repeat(0,(count($arr)-$key-1))."digit";
            $result[$key] = $char;
        }
        return $result;
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

    public function getMini()
    {
        return $this->mini;
    }

}

class NumbersUtil {

    // 移植
    public static $uraNumber4List = [
        0 => 5,
        1 => 6,
        2 => 7,
        3 => 8,
        4 => 9,
    ];
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

    /**
     * 移植　BOXの全パターンを出す。
     */
    private function getAllBoxNumbersPattern() {
        $result = [];
        foreach($this->allNumbersStringList as $numbers) {
            $number_array = str_split($numbers);
            sort($number_array);
            $result[] = implode($number_array);
        }
        return array_unique($result);
    }

    /*
     *  Box当選かどうか
     */
    public static function isBoxHit($numbers1Array, $numbers2Array) {
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
}


$NumbersPastData = new NumbersPastData(3);
$data = $NumbersPastData->getData();
var_dump($data);
//$StaticsService = new StaticsService($data);
//$StaticsService->displayAllResultNumbersCount('a',null);
