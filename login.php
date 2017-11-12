<?php
include("connect.php");
//login
if(!empty($_POST["login_mail"]).!empty($_POST["login_password"])){
  //id取得
  $stmt = "SELECT * FROM registration";
  $result = $pdo->query($stmt);
  $i=0;
  foreach($result as $row){
    if (($row['address']==$_POST['login_mail'])&&($row['status']==1)) {
      $i=$i+1;
    }
  }
  if($i==1){  //登録している
    //パスワードを確認
    $login_mail=$_POST["login_mail"];
    if ($row['password'] == $_POST["login_password"]){
      $login_name=$row['name'];
      //cookie
      setcookie("login_mail", $login_mail);
      setcookie("login_name", $login_name);
      echo "<script>alert('ログインしました')</script>";
      echo "<script language='javascript' type='text/javascript'>"."window.location.href='bulletinboard.php'"."</script>";
    }else{
      echo "<script>alert('idとパスワードを確認してください')</script>";
      echo "<script language='javascript' type='text/javascript'>"."window.location.href='login.html'"."</script>";
    }
  }else{
    echo "<script>alert('このアドレスは登録されていない')</script>";
    echo "<script language='javascript' type='text/javascript'>"."window.location.href='login.html'"."</script>";
  }
}else{
    echo "<script>alert('idとパスワードを入れてください')</script>";
    echo "<script language='javascript' type='text/javascript'>"."window.location.href='login.html'"."</script>";
}
?>
