<?php

require('./StatsRoyaleCondition.php');
require('./Scraper.php');

$StatsRoyaleCondition = new StatsRoyaleCondition();
$StatsRoyaleCondition->getTargetUrlList();
$Scraper = new Scraper($StatsRoyaleCondition);
$Scraper->execute();
var_dump($Scraper->getResultList());
// $Scraper->createResultJson();