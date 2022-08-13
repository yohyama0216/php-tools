<?php

class Converter {

    private $sourceHtmlFilePath = "%s-past-result.html";
    private $convertedFilePath = "%s-past-result.json";

    public function __construct($type)
    {
        $this->sourceHtmlFilePath = sprintf($this->sourceHtmlFilePath,$type);
        $this->convertedFilePath = sprintf($this->convertedFilePath,$type);
    }

    public function convertHtmlToJson()
    {
        $html = file_get_contents($this->sourceHtmlFilePath);

        if (!$html) {
            echo "ファイルは空です";
            return ;
        }

        $resultNumbersList = $this->extractResultNumbers($html);
        file_put_contents($this->convertedFilePath, json_encode($resultNumbersList));
    }

    private function extractResultNumbers($html)
    {
        $roundPattern = '#<tr class=.*[\s\S]*?</tr>#';
        preg_match_all($roundPattern,$html,$matches);

        $numberPattern = '#<td class="text-center text-bold">(\d{3,4})</td>#';
        $datePattern = '#<td nowrap="nowrap" class="text-center">(\d{4}/\d{2}/\d{2})</td>#';
        $roundPattern = '#<td nowrap="nowrap" class="text-center">(\d{4,5})</td>#';

        $roundData = [];
        foreach($matches[0] as $match){
            $round = $this->getStrings($roundPattern,$match);
            $roundData[$round] = [
                'date' => $this->getStrings($datePattern,$match),
                'numbers' => $this->getStrings($numberPattern,$match),
            ];
        }

        ksort($roundData);
        return $roundData;
    }

    private function getStrings($pattern,$subject)
    {
        preg_match($pattern,$subject,$number);
        $result = $number[1];
        if ($result) {
            return $result;
        } else {
            echo "空です".PHP_EOL;
            return "";
        }
    }
}

$type = $argv[1];
$converter = new Converter($type);
$converter->convertHtmlToJson();
