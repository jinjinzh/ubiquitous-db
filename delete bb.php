<?php
$pdo = new PDO("mysql:dbname=co_997_it_99sv_coco_com; host=localhost;", 'co-997.it.99sv-c', 'My6XREq');
$sql = "DROP TABLE IF EXISTS bulletinboard";
$pdo -> exec($sql);
?>
