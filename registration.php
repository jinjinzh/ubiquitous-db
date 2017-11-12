<?php
include("connect.php");
//登録フォームから値が送信
if(!empty($_POST["address"])){
  $sql = 'SELECT * FROM registration';
  $result = $pdo->query($sql);
  $i=0;
  foreach($result as $row){
    if (($row['address']==$_POST['address'])&&($row['status']==1)) {
      $i=$i+1;
    }
  }
  if($i==1){
    echo "<script>alert('This address has already been registered.')</script>";
    echo "<script language='javascript' type='text/javascript'>"."window.location.href='registration.html'"."</script>";
  }else{
    if(!empty($_POST["username"])){
      if(!empty($_POST["userpassword"])){
        if($_POST["userpassword"]==$_POST["userpassword2"]){
          //テーブルregistrationに追加
          $useraddress = trim($_POST["address"]);
          $username = stripslashes(trim($_POST["username"]));
          $userpassword = trim($_POST["userpassword"]);
          $regtime = time();
          $token = md5($username.$userpassword.$regtime);
          $token_exptime = time()+60*60*24;
          //INSERT
          $sql_reg = "INSERT INTO registration (token, token_exptime, regtime, address, name, password)
                      VALUES('$token', '$token_exptime', '$regtime', '$useraddress', '$username', '$userpassword')";
          $rslt_reg = $pdo->query($sql_reg);

          //mail
          $to = "$useraddress";
          $subject = "プログラミングブログ登録手続き";
          $link="http://co-997.it.99sv-coco.com/programmingblog/activation.php?verify=".$token;
          $message = $username."様、プログラミングブログの登録ありがとうございます。\r\n".
                    "引き続き以下のURLをクリックして、メール認証にお進みください。\r\n".
                    $link.
                    "\r\n▼お手続きにあたっての注意事項\r\n".
                    "メール認証手続きは、本メール到着から24時間以内に行ってください。\r\n".
                    "それ以上の時間が経過した場合は、最初からお手続きください。\r\n".
                    "▼このメールに心あたりがない場合\r\n".
                    "どなたかがあなたのメールアドレスを誤って入力されたものと思われます。\r\n".
                    "当メールを破棄くださいますようお願いいたします。";
          $from = "jingyz14@gmail.com";
          $headers = "From:" . $from;
          mail($to,$subject,$message, $headers);

          echo "<script>alert('".$_POST['address']."に認証メールを送信しましたので、確認してください。')</script>";
          echo "<script language='javascript' type='text/javascript'>"."window.location.href='login.html'"."</script>";
        }else{
          echo "<script>alert('パスワードが一致しない')</script>";
          echo "<script language='javascript' type='text/javascript'>"."window.location.href='registration.html'"."</script>";
        }
      }else{
        echo "<script>alert('パスワードを入れてください')</script>";
        echo "<script language='javascript' type='text/javascript'>"."window.location.href='registration.html'"."</script>";
      }
    }else{
      echo "<script>alert('名前を入れてください')</script>";
      echo "<script language='javascript' type='text/javascript'>"."window.location.href='registration.html'"."</script>";
    }
  }
}else{
  echo "<script>alert('メールアドレスを入れてください')</script>";
  echo "<script language='javascript' type='text/javascript'>"."window.location.href='registration.html'"."</script>";
}
?>
