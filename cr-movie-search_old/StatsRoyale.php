<?php

class Scraper
{
    private $pageRange = [];
    private $baseUrl = "http://statsroyale.com/ja/decks/challenge-winners?type=top200&page=";
    private $fileName = "./pages/page%s.json";
    private $resultList = [];

    public function __construct()
    {
        $this->pageRange = range(1,2);
    }

    public function scrape()
    {
        $result = [];
        foreach ($this->pageRange as $page) {
            $targetUrl = $this->baseUrl.$page;
            $pageHtml = file_get_contents($targetUrl);
            $watchUrlList = $this->getWatchUrlList($pageHtml);
            $key = 'page'.$page;
            $this->resultList[$key] = $this->getBattleResultList($watchUrlList);
        }
    }

    private function getWatchUrlList($html)
    {
        preg_match_all('/href="(https.*watch.top200.*)" class=/', $html, $matches);
        return $matches[1];
    }

    private function getBattleResultList($watchUrlList)
    {
        $battleResultList = [];
        foreach ($watchUrlList as $watchUrl) {
            $paths = explode('/', $watchUrl);
            $battleId = array_reverse($paths)[0];
            $battleResultList[$battleId] = $this->getBattleResult($watchUrl);
        }
        // ソートする？
        return $battleResultList;
    }

    private function getBattleResult($watchUrl)
    {
        $pageHtml = file_get_contents($watchUrl);
        return [
            'battleScore' => $this->getBattleScore($pageHtml),
            'players' => [
                'winner' => [
                    'id' => $this->getPlayerIdList($pageHtml)['winner'],
                    'deckCopyUrl' => $this->getPlayDeckCopyUrlList($pageHtml)['winner'],
                ],
                'loser' => [
                    'id' => $this->getPlayerIdList($pageHtml)['loser'],
                    'deckCopyUrl' => $this->getPlayDeckCopyUrlList($pageHtml)['loser'],
                ],
            ],
            'replayUrl' => $this->getMovieUrl($pageHtml)
        ];
    }

    private function getBattleScore($html)
    {
        preg_match('/<div class="replayLayout__resultValue ui__mediumText">(. - .)<.div>?/', $html, $matches);
        return $matches[1];
    }

    private function getPlayerIdList($html)
    {
        preg_match_all('/href=.http.*profile.(.*). class=/', $html, $matches);
        return [
            'winner' => $matches[1][0],
            'loser' => $matches[1][1]
        ];
    }

    private function getPlayDeckCopyUrlList($html)
    {
        preg_match_all('/href=.(http.*link.*). class=/', $html, $matches);
        return [
            'winner' => $matches[1][0],
            'loser' => $matches[1][1]
        ];
    }

    private function getMovieUrl($html)
    {
        preg_match('/iframe src="(http.*youtube.com.*)\?/', $html, $matches);
        return $matches[1];
    }

    public function createJson()
    {
        $data = $this->resultList;
        foreach($data as $key => $item) {
            $json = json_encode($item);
            $fileName = sprintf($this->fileName,$key);
            file_put_contents($fileName,$json);
        }
    }
}

$scraper = new Scraper();
$scraper->scrape();
$scraper->createJson();
