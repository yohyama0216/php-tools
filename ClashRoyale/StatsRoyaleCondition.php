<?php

class StatsRoyaleCondition
{
    private $baseUrl = "http://statsroyale.com/ja/decks/challenge-winners?type=top200&page=%s";
    private $range = [];
    private $targetUrlList = [];
    private $targetPattenList = [
        'movieUrl' => '#https://youtube.com/embed/.*?autoplay=1&showinfo=0#',
        'deckCopyUrl' => '#https://link.clashroyale.com/deck/.*deck=\d{8};\d{8};\d{8};\d{8};\d{8};\d{8};\d{8};\d{8}#',
    ];

    public function __construct()
    {
        $this->range = range(1,2);
        $this->targetUrlList = $this->createTargetUrlList();
    }

    public function getTargetUrlList()
    {
        return $this->targetUrlList;
    }

    public function getTargetPatternList()
    {
        return $this->targetPattenList;
    }

    private function createTargetUrlList()
    {
        $result = [];
        $pageUrlList = [];
        foreach($this->range as $num) {
            $pageKey = 'page'.$num;
            $pageUrlList[$pageKey] = sprintf($this->baseUrl,$num);
        }

        foreach($pageUrlList as $key => $pageUrl) {
            $result[$key] = $this->getTargetUrl($pageUrl);
        }
        return $result;
    }    

    private function getTargetUrl($pageUrl)
    {
        $html = file_get_contents($pageUrl);
        $pattern = '#<a href="(.*)?" class="recentWinners__footerAction ui__layoutOneLine ui__link">#';
        preg_match($pattern, $html, $matches);
        return $matches[1];
    }
}
