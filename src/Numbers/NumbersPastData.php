<?php

namespace Test\Numbers;

class NumbersPastData {
    private $sourceFile = "./data/numbers3-past-result.json";
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