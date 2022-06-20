<?php
include_once("../main.php");

$row = 1;
if (($handle = fopen(PLANILHA_IMPORTACAO, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<b> $num campos na linha $row: <br /></b>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
        echo "<br>";
    }
    fclose($handle);
}


?>
