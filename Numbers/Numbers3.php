<?php

class Numbers3 {
    private $sourceFile = "../pastResult/numbers3-past-result.json";
    private $pastData;
    private $StaticsService;
    private $PredictService;

    public function __construct()
    {
        $this->pastData = $this->createPastData();
        $this->StaticsService = new StaticsService($this->pastData);
        $this->PredictService = new PredictService($this->pastData);
    }

    /*
     *  それぞれの桁に分ける
     */
    public function createPastData()
    {
        $result = [];
        $data = json_decode(file_get_contents($this->sourceFile),true);
        foreach($data as $key => $item) {
            $arr = str_split($item['numbers']);
            $result[$key]['numbers'] = $arr;
        }
        return $result;
    }

    public function displayStatics()
    {
        $this->StaticsService::displayAllNumbersCount($this->pastData, 'asc', 20);
        $mini = $this->StaticsService::getAllNumbersMini($this->pastData);
        $this->StaticsService::displayAllNumbersCount($mini, 'asc', 20);
        //$this->StaticsService::displayAllNumbersMiniCount('asc', 20);
        // $this->StaticsService->displayStaticsConsecutiveHit(2); // 0.12 
        // $this->StaticsService->displayStaticsConsecutiveDifferentCharOneNumbers(2); // 0.55
        // $this->StaticsService->displayStaticsRoundsHitWithInPreviousNumbers(1700); // 54.62
        // $this->StaticsService->displayStaticsRoundsHitWithSameNumber(); // 0.81%
        // $this->StaticsService->displayStaticsRoundsHitWithPlusoneNumber(); // 0.81%
    }

    public function predict()
    {
        $this->PredictService->predict();
    }
}

$numbers3 = new Numbers3();
$numbers3->displayStatics();
$numbers3->predict();
// 3桁の数字が連続 123, 456など
// 両端の数字が同じ 121など
// 前回数字と数字が一つだけ同じ 123→145とか
// 前回数字とひっくり返した数字　123 → 321など

class StaticsService {
    private $totalCount;
    private $showRounds = false;

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
     *  数字の出現回数
     *  @param $order 順序
     *  @param $limit 表示件数
     */
    public static function displayAllNumbersCount($data, $order = 'desc', $limit = null)
    {
        $result = [];
        echo __METHOD__.PHP_EOL;
        foreach($data as $round => $numbers) {
            $key = '['.implode($numbers['numbers']).']';
            if (!array_key_exists($key,$result)) {
                $result[$key] = 1;
            } else {
                $result[$key] += 1;
            }
        }
        if ($order == 'desc'){
            asort($result);
        } else {
            arsort($result);
        }

        if (is_int($limit)) {
            $result = array_chunk($result,$limit,true)[0];
        }
        foreach($result as $key => $item) {
            echo "$key が $item 回".PHP_EOL;
        }
    }

    /*
     *  数字の出現回数
     *  @param $order 順序
     *  @param $limit 表示件数
     */
    public static function getAllNumbersMini($data)
    {
        foreach($data as $key => $item) {
            $data[$key]['numbers'] = [
                $item['numbers'][1],$item['numbers'][2]
            ];
        };
        return $data;
    }


    private function getProbabilityPerTotal($roundCount)
    {
        return round($roundCount / $this->totalCount * 100, 2) ."%";
    }

    /*
     * 連続かつ±1,±10,±100の数字が出た回。の統計を表示する。
     * 
     * @params $times n回の連続　(3回以上は無い模様) 
     */
    public function displayStaticsConsecutiveDifferentCharOneNumbers($times)
    {
        echo $times."回連続かつ±1,±10,±100の数字が出た回。".PHP_EOL; //Numbers3に依存
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
                $array[] = $this->data[(int)$round+$i]['numbers']; // 2回しかないなら、もっと単純に。
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
        echo "全".$this->totalCount."中、".count($rounds)."回 : ".$this->getProbabilityPerTotal(count($rounds));
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

            $array = $this->getNumbersRange($this->data, $round, $previous, 1);

            if (in_array($this->data[(int)$round]['numbers'],$array)) {
                $rounds[] = $round;
            } else {
                continue ;
            }
        }
        return $rounds;
    }

    private function getNumbersRange($data, $start, $end, $step){
        $array = [];
        for($i=1;$i<=$end;$i++){
            $array[] = (int)$this->data[(int)$start+$i*$step]['numbers'];
        }
        return $array;
    }


    public function displayStaticsRoundsHitWithSameNumber()
    {
        echo "全桁とも同じ数字が出た回。".PHP_EOL;
        $rounds = $this->getRoundsHitWithSameNumber();

        $this->displayResultMessages($rounds);
    }

    /*
     * 全桁とも同じ数字が出た回を取得する。
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

    public function displayStaticsRoundsHitWithPlusoneNumber()
    {
        echo "連続した数字が出た回。".PHP_EOL;
        $rounds = $this->getRoundsHitWithPlusoneNumber();

        $this->displayResultMessages($rounds);
    }
    /*
     * 123のような連続した数字が出た回を取得する。
     * 
     */
    public function getRoundsHitWithPlusoneNumber()
    {
        $rounds = [];
        foreach($this->data as $round => $item) {
            $numbersArray = str_split($item['numbers']);
            if ($numbersArray[0] + 1 == $numbersArray[1]
            && $numbersArray[1] + 1 == $numbersArray[2]) { // Numbers3に依存
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
class PredictService {
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function predict()
    {
        // $data = $this->getPreviousNumberRange($data, $start, $end, $step);
        // $data = $this->filterSameNumber($data);
        // $data = $this->filterPlusOneNumber($data);
    }

    private function filterSameNumbers()
    {

    }
}