<?php
include("connect.php");

$verify=stripslashes(trim($_GET['verify']));
$nowtime=time();

$sql = 'SELECT * FROM registration';
$result = $pdo->query($sql);
foreach($result as $row){
  if($row['token'] == $verify){
    if($nowtime > $row['token_exptime']){ //30min
    	$expiredReg="delete from registration where id = {$row['id']}";
    	$pdo->query($expiredReg);
      $msg='This activation is expired, please register with you mail address again.';
    }else{
      $activate="update registration set status=1 where id = {$row['id']}";
      $pdo->query($activate);
      $msg='Your account is activated now.';
    }
  }else{
    $msg='Error.';
  }
}
echo "<script>alert('".$msg."')</script>";

?>
