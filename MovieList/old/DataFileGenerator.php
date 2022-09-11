<?php

class DataFileGenerator
{
    private $fileName = "./pages/%s.json";

    public static function generateJson($key,$resultList)
    {
        if (empty($resultList)) {
            echo "this is empty";
        } 

        $json = json_encode($resultList);
        $fileName = sprintf(self::$fileName,$key);
        file_put_contents($fileName,$json);
        echo $fileName." saved".PHP_EOL;
    }
}
