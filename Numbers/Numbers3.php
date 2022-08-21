<?php

class Numbers3 {
    private $sourceFile = "../pastResult/numbers3-past-result.json";
    private $NumbersPastData;
    private $StaticsService;
    private $PredictService;

    public function __construct() {
        $data = json_decode(file_get_contents($this->sourceFile), true);
        $this->NumbersPastData = new NumbersPastData($data);
        $this->StaticsService = new StaticsService($this->NumbersPastData->getData());
        // $this->PredictService = new PredictService();
    }

    public function displayStatics() {
        $this->StaticsService->displayAllNumbersCount($this->NumbersPastData, 'asc', 20);
    }

    public function predict() {
        $this->PredictService->predict();
    }
}

class NumbersPastData {
    private $numbersType = "";
    private $data = [];

    public function __construct($data, $start = null, $end = null) {
        $this->data = $this->createPastData($data, $start, $end);
    }

    public function  getData()
    {
        return $this->data;
    }

    /*
     *  それぞれの桁に分ける
     */
    private function createPastData($data, $start, $end) {
        $result = [];
        if ($start && $end) {
            $data = array_slice($data, $start, $end);
        }
        foreach ($data as $key => $item) {
            $arr = str_split($item['numbers']);
            $result[$key]['numbers'] = $arr;
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

    public function getRoundListByNumbers($numbers) {
        $result = [];
        foreach ($this->data as $key => $item) {
            if ($item['numbers'] == $numbers) {
                $result[] = $key;
            }
        }
        return $result;
    }
}

class StaticsService {
    private $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    /*
     *  数字の出現回数
     *  @param $order 順序
     *  @param $limit 表示件数
     */
    public function displayAllNumbersCount($order = 'desc', $limit = null) {
        $result = [];
        echo __METHOD__ . PHP_EOL;
        foreach ($this->data as $round => $numbers) {
            $key = '[' . implode($numbers['numbers']) . ']';
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
}

$numbers3 = new Numbers3();
$numbers3->displayStatics();
