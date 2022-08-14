<?php

class Numbers3 {
    private $sourceFile = "../pastResult/numbers3-past-result.json";
    private $data;
    private $totalCount;
    private $showRounds = false;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents($this->sourceFile),true);
        $this->totalCount = count($this->data);
    }

    public function getNumbersByRound($round)
    {
        return $this->data[$round];
    }

    private function getProbabilityPerTotal($roundCount)
    {
        return round($roundCount / $this->totalCount * 100, 2) ."%";
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
     * 連続かつ±1,±10,±100の数字が出た回。の統計を表示する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function displayStaticsConsecutiveDifferentCharOneNumbers($times)
    {
        echo $times."回連続かつ±1,±10,±100の数字が出た回。".PHP_EOL;
        $rounds = $this->getConsecutiveDifferentCharOneNumbers(2);
        $this->displayResultMessages($rounds);
    }
    /*
     * 連続かつ±1,±10,±100の数字が出た回を表示する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function getConsecutiveDifferentCharOneNumbers($times)
    {
        $rounds = [];
        foreach($this->data as $round => $result) {
            if ((int)$round+ $times >= count($this->data)) {
                break;
            }

            $array = [];
            for($i=0;$i<$times;$i++){
                $array[] = $this->data[(int)$round+$i]['numbers'];
            }

            if ($this->getCondition($array, 'differentOneNumber')) {
                $rounds[] = $round;
            } else {
                continue ;
            }
        }
        return $rounds;        
    }

    private function displayResultMessages($rounds)
    {
        if ($this->showRounds){
            foreach($rounds as $round) {
                echo $round."回".PHP_EOL;
            }
        }
        echo "全".$this->totalCount."中、".$this->totalCount."回 : ".$this->getProbabilityPerTotal(count($rounds));
        echo "-----------".PHP_EOL;
    }

    /*
     * 連続して同じ数字が出た回の統計を表示する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function displayStaticsConsecutiveHit($times)
    {
        echo $times."回連続で同じ数字が出た回。".PHP_EOL;
        $rounds = $this->getRoundsConsecutiveHit($times);

        $this->displayResultMessages($rounds);
    }

    /*
     * 連続して同じ数字が出た回を取得する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function getRoundsConsecutiveHit($times)
    {
        $rounds = [];
        foreach($this->data as $round => $item) {
            if ((int)$round+ $times >= count($this->data)) {
                break;
            }

            $array = [];
            for($i=0;$i<$times;$i++){
                $array[] = $this->data[(int)$round+$i]['numbers'];
            }

            if ($this->getCondition($array, 'allSame')) {
                $rounds[] = $round;
            } else {
                continue ;
            }
        }
        return $rounds;
    }

    public function displayStaticsRoundsHitWithInPreviousNumbers($previous)
    {
        echo $previous."回さかのぼって同じ数字が出た回。".PHP_EOL;
        $rounds = $this->getRoundsHitWithInPreviousNumbers($previous);

        $this->displayResultMessages($rounds);
    }

    /*
     * 過去、$previous回さかのぼって同じ数字が出た回を取得する。
     * 
     * @params $previous さかのぼるn回
     */
    public function getRoundsHitWithInPreviousNumbers($previous)
    {
        $rounds = [];
        foreach($this->data as $round => $item) {
            if ((int)$round+ $previous >= count($this->data)) {
                break;
            }

            $array = [];
            for($i=1;$i<=$previous;$i++){
                $array[] = (int)$this->data[(int)$round+$i]['numbers'];
            }

            if (in_array($this->data[(int)$round]['numbers'],$array)) {
                $rounds[] = $round;
            } else {
                continue ;
            }
        }
        return $rounds;
    }


    public function displayStaticsRoundsHitWithSameNumber()
    {
        echo "3桁とも同じ数字が出た回。".PHP_EOL;
        $rounds = $this->getRoundsHitWithSameNumber();

        $this->displayResultMessages($rounds);
    }

    /*
     * 3桁とも同じ数字が出た回を取得する。
     * 
     */
    public function getRoundsHitWithSameNumber()
    {
        $rounds = [];
        foreach($this->data as $round => $item) {
            $numbersArray = str_split($item['numbers']);
            if (count(array_unique($numbersArray)) == 1) {
                $rounds[] = $round;
            } else {
                continue ;
            }
        }
        return $rounds;
    }
    private function getCondition($array, $type)
    {
        if ($type == 'allSame') {
            return (count(array_unique($array)) == 1);
        } else if ($type == 'twoSame') {
            return (count(array_unique($array)) == 2);
        } else if ($type == 'leftCharSame') {

        } else if ($type == 'rightCharSame') {

        } else if ($type == 'middleCharSame') {

        } else if ($type == 'differentOneNumber') {
            return (
                in_array(abs($array[1] - $array[0]),[1,10,100]) 
            );
        }
    }
}

$numbers3 = new Numbers3();
$numbers3->displayStaticsConsecutiveHit(2); // 0.12 
$numbers3->displayStaticsConsecutiveDifferentCharOneNumbers(2); // 0.55
$numbers3->displayStaticsRoundsHitWithInPreviousNumbers(1700); // 54.62
$numbers3->displayStaticsRoundsHitWithSameNumber(); // 0.81%
// 3桁の数字が連続 123, 456など
// 両端の数字が同じ 121など
// 前回数字と数字が一つだけ同じ 123→145とか
// 前回数字とひっくり返した数字　123 → 321など
// 