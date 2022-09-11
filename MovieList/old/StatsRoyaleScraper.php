<?php

require('./DataFileGenerator.php');
require('./Scraper.php');

class StatsRoyaleScraper
{
    private $pageRange = [];
    private $baseUrl = "http://statsroyale.com/ja/decks/challenge-winners?type=top200&page=";
    private $baseWatchUrlPattern = '/href="(https.*watch.top200.*)" class=/';
    private $Scraper;

    public function __construct($Scraper)
    {
        global $argv;
        $start = $argv[1];
        $end = $argv[2];
        $this->pageRange = range($start,$end);
    }

    public function scrape()
    {
        $result = [];
        foreach ($this->pageRange as $page) {
            $url = $this->baseUrl.$page;
            $Scraper = new Scraper($url);
            $watchUrlList = $Scraper->getTargetTextList($this->baseWatchUrlPattern);
            $fileName = 'page'.$page;
            $resultList = $this->createScrapeResultList($watchUrlList);
            DataFileGenerator::generateJson($fileName,$resultList);
        }
    }

    private function createScrapeResultList($watchUrlList)
    {
        $battleResultList = [];
        foreach ($watchUrlList as $watchUrl) {
            $paths = explode('/', $watchUrl);
            $battleId = array_reverse($paths)[0];
            $battleResultList[$battleId] = $this->createScrapeResult($watchUrl);
        }
        // ソートする？
        return $battleResultList;
    }

    private function createScrapeResult($watchUrl)
    {
        $Scraper = new Scraper($watchUrl);
        return [
            'battleScore' => $this->getBattleScore($Scraper),
            'players' => [
                'winner' => [
                    'id' => $this->getPlayerIdList($Scraper)['winner'],
                    'deckCopyUrl' => $this->getPlayDeckCopyUrlList($Scraper)['winner'],
                ],
                'loser' => [
                    'id' => $this->getPlayerIdList($Scraper)['loser'],
                    'deckCopyUrl' => $this->getPlayDeckCopyUrlList($Scraper)['loser'],
                ],
            ],
            'replayUrl' => $this->getMovieUrl($Scraper)
        ];
    }

    private function getBattleScore($Scraper)
    {
        $pattern = '/<div class="replayLayout__resultValue ui__mediumText">(. - .)<.div>?/';
        return $Scraper->getTargetTextList($pattern);
    }

    private function getPlayerIdList($Scraper)
    {
        $pattern =  '/href=.http.*profile.(.*). class=/';
        $result = $Scraper->getTargetTextList($pattern);
        return [
            'winner' => $result[0],
            'loser' => $result[1]
        ];
    }

    private function getPlayDeckCopyUrlList($Scraper)
    {
        $pattern = '/href=.(http.*link.*). class=/';
        $result = $Scraper->getTargetTextList($pattern);
        return [
            'winner' => $result[0],
            'loser' => $result[1]
        ];
    }

    private function getMovieUrl($Scraper)
    {
        $pattern = '/iframe src="(http.*youtube.com.*)\?/';
        return $Scraper->getTargetTextList($pattern);
    }
}

$scraper = new StatsRoyaleScraper();
$scraper->scrape();
