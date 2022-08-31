<?php



class Numbers {
    private $numbersType = '';
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

// ?? 意味ある？
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




}
