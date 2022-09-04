<?php

use Test\Numbers\NumbersPastData;

require('vendor/autoload.php');

//$Converter = new Test\Converter('numbers3');
//$html = $Converter->generateResultSQL();

$NumbersPastData = new NumbersPastData(3);
//$data = $NumbersPastData->getData();
$Search = new Test\Search\Search($NumbersPastData);
$Search->searchNumbersDigitPattern(10,2,'1digit');
$Search->displayResult();

?>
<html>
<head></head>
<body>
    <main>
        test <?php  ?>
    </main>
</body>
</html>