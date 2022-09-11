<?php

class Scraper
{
    private $fileName = 'result.json';
    private $ScrapingCondition = null;
    private $resultList = [];

    public function __construct($ScrapingCondition)
    {
        $this->ScrapingCondition = $ScrapingCondition;
    }

    public function getResultList()
    {
        return $this->resultList;
    }

    public function execute()
    {
        $urlList = $this->ScrapingCondition->getTargetUrlList();
        $targetPatternList = $this->ScrapingCondition->getTargetPatternList();
        foreach($urlList as $url) {
            $temp = [];
            foreach($targetPatternList as $target => $pattern) {
                $temp[$target] = $this->scrape($url, $pattern);
            }
            $this->resultList[] = $temp;
        }
    }

    private function scrape($url, $pattern)
    {
        $html = file_get_contents($url);
        preg_match_all($pattern, $html, $matches);
        return $matches[0];
    }

    public function createResultJson()
    {
        return file_put_contents($this->fileName,json_encode($this->resultList));
    }
}
