<?php

class Scraper
{
    private $number = 0;
    private $fileName = './result%d.json';
    private $StatsRoyaleCondition = null;
    private $resultList = [];

    public function __construct($StatsRoyaleCondition)
    {
        $this->StatsRoyaleCondition = $StatsRoyaleCondition;
        $this->number = $StatsRoyaleCondition->getRangeStart();
    }

    public function getResultList()
    {
        return $this->resultList;
    }

    public function execute()
    {
        $urlList = $this->StatsRoyaleCondition->getTargetUrlList();
        $targetPatternList = $this->StatsRoyaleCondition->getTargetPatternList();
        $fileName = 1;
        foreach($urlList as $url) {
            $data = [];
            foreach($targetPatternList as $target => $pattern) {
                $data[$target] = $this->scrape($url, $pattern);
            }
            $this->resultList[] = $data;
            $this->createResultJson();
        }
    }

    private function scrape($url, $pattern)
    {
        $html = file_get_contents($url);
        preg_match_all($pattern, $html, $matches);
        return $matches[0];
    }

    private function createResultJson()
    {
        echo count($this->resultList).PHP_EOL;
        if (count($this->resultList) % 9 == 0) {
            $fileName = sprintf($this->fileName,$this->number); 
            file_put_contents($fileName,json_encode($this->resultList));
            echo "$fileName created".PHP_EOL;
            $this->resultList = [];
            $this->number += 1;
        }
    }
}
