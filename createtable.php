<?php
include("connect.php");
//テーブル作成
$sql_create = 'CREATE TABLE IF NOT EXISTS registration
    (
      id int NOT NULL AUTO_INCREMENT,
      token varchar(50) NOT NULL,
      token_exptime int(10) NOT NULL,
      regtime int(10) NOT NULL,
      address varchar(255) NOT NULL,
   	  name varchar(255) NOT NULL,
   	  password varchar(32) NOT NULL,
      status tinyint(8) NOT NULL DEFAULT 0,
   	  PRIMARY KEY(id)
   	 );';
$pdo->query($sql_create);

//bulletinboard
$sql_create2 = 'CREATE TABLE bulletinboard
    (
      id int NOT NULL AUTO_INCREMENT,
      address varchar(255) NOT NULL,
  	  name varchar(255) NOT NULL,
  	  comment text(65532) NOT NULL,
      tag varchar(128) NOT NULL,
      filename varchar(65532) NOT NULL,
      filetype varchar(255) NOT NULL,
  	  datetime varchar(255) NOT NULL,
  	  PRIMARY KEY(id)
  	 );';
$rslt_create2 = $pdo->query($sql_create2);
?>
