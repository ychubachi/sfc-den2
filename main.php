<?php

$questionID = $_POST["questionID"];
$questionAnswerID = $_POST["questionAnswerID"];
$comment =$_POST["comment"];

//DBへの接続の準備
$dsn = 'mysql:dbname=den2_db;host=mysql408.db.sakura.ne.jp';
$user = 'den2';
$passwd = 'den2den2den2';

try {
   $pdo = new PDO($dsn, $user, $passwd);
//投票数の更新
   $sql = 'UPDATE questionAnswer SET QuestionAnswerCount = QuestionAnswerCount + 1,QuestionAnswerPoint = QuestionAnswerPoint + 1 WHERE QuestionID = ? and QuestionAnswerID = ? ';   
   $stmt = $pdo->prepare($sql);
   $stmt->execute(array($questionID,$questionAnswerID));
   //コメントの保有
   //Commentは８０字以内のコメントを保有
   $stmt = $pdo->query("SET NAMES utf8;");
   $stmt = $pdo->query($sql);
   $sql = 'INSERT INTO QuestionAnswerComment (QuestionID,QuestionAnswerID,Comment) VALUES (?,?,?)';
       $stmt = $pdo->prepare($sql);
      $stmt->execute(array($questionID,$questionAnswerID,$comment));
} catch( PDOException $e ) {
// DBアクセスができなかったとき
  echo 'Connection failed(1): ' . $e->getMessage();
  $pdo = null;
  die();
}

try {
  $pdo = new PDO($dsn, $user, $passwd);
  $sql = 'SELECT QuestionAnswer, QuestionAnswerCount, QuestionAnswerPoint FROM questionAnswer ';
//文字コードをutf8に指定
  $stmt = $pdo->query("SET NAMES utf8;");
  $stmt = $pdo->query($sql);
//  $pdo = null;
} catch( PDOException $e ) {
// DBアクセスができなかったとき
  echo 'Connection failed(2): ' . $e->getMessage();
  $pdo = null;
  die();
}



 //何よう、、？
// $sql = 'SELECT QuestionAnswerComment SET QuestionID = questionAnswer(QuestionID)'
// $pdo->query($sql);
?>


<html>
<head>
<meta charset="UTF-8">
<title>PHP TEST</title>
</head>
<body>
<table border="1" cellpadding="5">
<caption>アンケート結果</caption>
<tr>
    <th>答え</th>
    <th>カウント</th>
    <th></th>
</tr>
<?php
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print('<td>'.$result['QuestionAnswer'].'</td>');
    print('<td><div style="background-color: blue; width: '.$result['QuestionAnswerCount'].' px; font-size: 10px;">&nbsp;</div></td>');
    print('<td>'.$result['QuestionAnswerCount'].' 票</td>');
    print('</tr>');
  }
?>
</table>

<table border="1" cellpadding="5">
<caption>地域別</caption>
<tr>
    <th>答え</th>
    <th>Point</th>
    <th></th>
</tr>
<?php
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print('<td>'.$result['QuestionAnswer'].'</td>');
    print('<td><div style="background-color: red; width: '.$result['QuestionAnswerPoint'].' px; font-size: 10px;">&nbsp;</div></td>');
    print('<td>'.$result['QuestionAnswerPoint'].' P</td>');
    print('</tr>');
  }
?>
</table>


<br>
<Form><Input type=button value="アンケート画面に戻る" onClick="javascript:history.go(-1)"></Form>

  </body>
</html>