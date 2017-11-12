<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>プログラミングブログ掲示板</title>
</head>

<?php
include("connect.php");
//id,name取得
$login_mail=$_COOKIE["login_mail"];
$login_name=$_COOKIE["login_name"];
?>

<body>
<h5>Welcome! <?php echo $login_name; ?> (<?php echo $login_mail; ?>)
<form action="logout.php" method="post">
  <input type="submit" name="logout" value="ログアウト">
</form>
</h5>
<hr/>
<h1>掲示板</h1>
<hr/>
<h3>皆さんの好きな音楽、または歌手を教えてください！</h3>

<?php
//file upload
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
  $uploadtime = date("YmdHis");
  $file = md5($uploadtime.$login_mail);
  move_uploaded_file($_FILES['file']['tmp_name'],"$file");
  $ext_img=array("image/jpg",
                "image/gif",
                "image/bmp",
                "image/jpeg",//jpeg
                "image/pjpeg",
                "image/png");
  $ext_video=array("video/x-flv",
                  "video/mp4",
                  "video/3gpp",
                  "video/quicktime",
                  "video/x-msvideo",
                  "application/x-troff-msvideo",//avi
                  "video/avi",
                  "video/msvideo",
                  "video/x-ms-wmv");
  if(in_array($_FILES['file']['type'],$ext_img)){
    $tag="img";
    //echo $filetype=$_FILES['file']['type'];
  }elseif(in_array($_FILES['file']['type'],$ext_video)){
    $tag="video";
    //echo $filetype=$_FILES['file']['type'];
  }else{
    echo "<script>alert('This type of file is not available now. You can upload a jpg/gif/bmp/jpeg/png/flv/mp4/3gp/quicktime/avi/wmv file.')</script>";
    echo "<script language='javascript' type='text/javascript'>"."window.location.href='bulletinboard.html'"."</script>";

  }
}

//form
//編集フォームから値が送信された場合
if(!empty($_POST["edit"])){
	//番号取得
	$stmt = "SELECT * FROM bulletinboard";
	$result = $pdo->query($stmt);
	foreach($result as $row){
		//投稿番号と編集番号が一致する場合
		if($row['id'] == $_POST["edit"]){
			//addressを確認
			if ($row['address'] == $login_mail){
				$edit_comment=$row['comment'];
			}else{
				echo "<script>alert('投稿番号を確認してください')</script>";
			}
		}
	}
}

//削除フォームから値が送信された場合
if(!empty($_POST["dnum"])){
	//番号取得
	$stmt = "SELECT * FROM bulletinboard";
	$result = $pdo->query($stmt);
	foreach($result as $row){
		//投稿番号と削除番号が一致する場合
		if($row['id'] == $_POST["dnum"]){
			//addressを確認
			if ($row['address'] == $login_mail){
				//削除
				$sql_del="delete from bulletinboard where id={$row['id']}";
				$rslt_del=$pdo->query($sql_del);
			}else{
				echo "<script>alert('投稿番号を確認してください')</script>";
			}
		}
	}
}

//コメント送信
if(!empty($_POST["comment"])){
	if("" != $_POST["mode_edit"]){
    //編集モードの場合
		$comment = $_POST["comment"];
		$datetime = date("Y/m/d H:i:s");
		//編集
		$sql_edit = "update bulletinboard set
              comment='$comment',
              tag='{$_POST["tag"]}',
              filename='{$_POST["filename"]}',
              filetype='{$_POST["filetype"]}',
              datetime='$datetime'
              where id = {$_POST["mode_edit"]}
                ";
		$rslt_edit = $pdo->query($sql_edit);
	}elseif("" == $_POST["mode_edit"]){
    //新しい投稿の場合
		$comment = $_POST["comment"];
		$datetime = date("Y/m/d H:i:s");

		//INSERT
		$sql_insert = "INSERT INTO bulletinboard (address, name, comment, tag, filename, filetype, datetime)
                  VALUES('$login_mail', '$login_name', '$comment', '{$_POST["tag"]}', '{$_POST["filename"]}', '{$_POST["filetype"]}', '$datetime')";
		$rslt_insert = $pdo->query($sql_insert);
	}
}
?>

<table>
<form action="" method="post" id="bbform">
    <input type = "hidden" name = "mode_edit" value=<?php echo $_POST["edit"]; ?>>
    <input type = "hidden" name = "tag" value=<?php echo $tag; ?>>
    <input type = "hidden" name = "filename" value=<?php echo $file; ?>>
    <input type = "hidden" name = "filetype" value=<?php echo $filetype; ?>>
  <tr>
    <td>名前</td>
    <td><input type="text" name="name" value="<?php echo $login_name; ?>" readonly></td>
  </tr>
<form action="" method="post" enctype="multipart/form-data">
  <tr>
    <td>画像/ビデオ</td>
    <td><input type="file" name="file"></td>
    <td><input type="submit" name="upload" value="アプロード"></td>
  </tr>
  <tr>
    <td>　</td>
    <td>添付ファイル：<?php if($_FILES['myFile']['error']==0){echo $_FILES['file']['name'];} ?></td>
  </tr>
</form>
  <tr>
    <td>コメント</td>
    <td><textarea rows="8" cols="60" name="comment" form="bbform"><?php echo $edit_comment; ?></textarea></td>
    <td><input type="submit"></td>
  </tr>
</form>
    <td>　</td>
<form action="" method="post">
  <tr>
    <td>編集対象番号</td>
    <td><input type = "text" name = "edit">
      <input type = "submit" name = "toedit" value = "編集"></td>
  </tr>
  <tr>
    <td>削除対象番号</td>
    <td><input type = "text" name = "dnum">
      <input type = "submit" name = "delete" value = "削除"></td>
  </tr>
</form>
</table>


<h2>コメント</h2>
<hr/>
<p>

<?php
include("connect.php");

$sql = 'SELECT * FROM bulletinboard ORDER BY id ASC';
$result = $pdo->query($sql);
//if(is_array($result)){
  foreach($result as $row){
    echo "#".$row['id']."<br/>";
    echo "名前：".$row['name']."<br/>";
    echo "コメント：".$row['comment']."<br/>";
    //echo $row['tag']."<br/>";
    //echo $row['filename']."<br/>";
    $filename=$row['filename'];
    $filetype=$row['filetype'];
    if($row['tag']=="img"){
      echo "<img src="."$filename"." width='500'>";
      echo "<br/>";
    }elseif($row['tag']=="video"){
      echo "<video width='500' controls='controls'>
            <source src="."$filename"." type="."$filetype".">
            </video>";
      echo "<br/>";
    }
    echo "投稿時間：".$row['datetime']."<br/><br/>";
  }

?>

</p>
</body>
</html>
