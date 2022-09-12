<?php

require('./StatsRoyaleCondition.php');
require('./Scraper.php');
$start = $argv[1];
$end = $argv[2];
$StatsRoyaleCondition = new StatsRoyaleCondition($start,$end);
$StatsRoyaleCondition->getTargetUrlList();
$Scraper = new Scraper($StatsRoyaleCondition);
$Scraper->execute();
var_dump($Scraper->getResultList());
// $Scraper->createResultJson();