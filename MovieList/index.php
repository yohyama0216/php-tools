<?php

require('./ScrapingCondition.php');
require('./Scraper.php');

$ScrapingCondition = new ScrapingCondition();
$ScrapingCondition->getTargetUrlList();
$Scraper = new Scraper($ScrapingCondition);
$Scraper->execute();
var_dump($Scraper->getResultList());
// $Scraper->createResultJson();