<?php

use Test\Numbers\NumbersPastData;

require('vendor/autoload.php');

//$Converter = new Test\Converter('numbers3');
//$html = $Converter->generateResultSQL();

$NumbersPastData = new NumbersPastData(3);
$data = $NumbersPastData->getData();
$Search = new Test\Search\Search($data);
$Search->searchSameDigitNumbers();
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