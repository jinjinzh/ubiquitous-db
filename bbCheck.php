<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("connect.php");
$sql = 'SELECT * FROM bulletinboard';
$result = $pdo->query($sql);
foreach($result as $row){
    echo $row['id'].',';
    echo $row['address'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['tag'].',';
    echo $row['filename'].',';
	  echo $row['filetype'].'<br>';
}
?>
