<?php

class Dividend
{
    private $basePath = "https://www.bloomberg.co.jp/quote/%s:US";
    private $etfList = [
        'AGG',
        'DGRW',
        'VTI',
        'TIP',
        'SOXL',
        'DIA',
        'VHT',
        'QYLD',
        'QYLG',
        'XYLD',
        'XYLG',
    ];
    private $urlList = [];

    public function __construct()
    {
        foreach($this->etfList as $etf) {
            $this->urlList[$etf]['url'] = sprintf($this->basePath, $etf); 
        }
    }

    public function getEtfInfo()
    {
        //$result = [];
        foreach($this->urlList as $etf => $url) {
            $html = file_get_contents($url['url']);
            $pricePattern = '#<div class="price">(.{5,6})?</div><!-- no#';
            $dividendPattern ='#直近配当額 (\d{2}/\d{2}/\d{4}.*)?</div>#';
            $this->urlList[$etf]['price'] = $this->getString($pricePattern, $html);
            $this->urlList[$etf]['dividend'] = $this->getString($dividendPattern, $html);
        }
        var_dump($this->urlList);
    }

    private function getString($pattern,$subject)
    {
        preg_match_all($pattern,$subject,$matches);
        return $matches[1];
    }
}
$Dividend = new Dividend();
$Dividend->getEtfInfo();