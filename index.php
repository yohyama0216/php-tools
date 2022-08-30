<?php
require('vendor/autoload.php');

$Converter = new Test\Converter('numbers3');

$html = $Converter->generateResultSQL();
?>
<html>
<head></head>
<body>
    <main>
        test <?php echo $html ?>
    </main>
</body>
</html>