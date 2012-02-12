<?php
require 'php-sdk/src/facebook.php';

// インスタンス生成
$facebook = new Facebook(array(
  'appId'  => '185962178177200', // for YC
  'secret' => '7d297d8f025ab9497ffb7a8267f7d16c' // YC
));

// ユーザＩＤ取得
$user = $facebook->getUser();

// $user_id = $facebook->require_login("publish_stream");

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(
    'scope' => 'publish_stream,user_birthday'
  ));
}


?>
<!doctype html>
<meta charset="utf-8">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Facebookアンケートアプリ－vote</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
どっちがイヤ？
  <?php
    session_start();

    if (isset($_SESSION["USERID"])) {
      $errorMessage = "ログアウトしました。";
    }
    else {
      $errorMessage = "セッションがタイムアウトしました。";
    }
    $name_p1 = "きゃりーぱみゅぱみゅ";
    $name_p2 = "温水洋一";
    $img_p1 = "images/pmpm.jpg";
    $img_p2 = "images/nknk.jpg";
    ?>

    <form action = "main.php" method = "POST">
    <div id = "questionAnswerID01">

    <?php print "<img src = {$img_p1}><br/>\n"; ?>
    <?php print "$name_p1<br/>\n"; ?>
    <input type = "hidden" name="questionID" value="01">
    <input type = "hidden" name="questionAnswerID" value="01">
    <input type = "text" name = "comment" maxlength = "80" />
    <input type = "submit" name = "submit1" value = "投票" />

    </form>
    </div>

    <form action = "main.php" method = "POST">
    <div id = "questionAnswerID02">

    <?php print "<img src = {$img_p2}><br/>\n"; ?>
    <?php print "$name_p2<br/>\n"; ?>
    <input type = "hidden" name="questionID" value="01">
    <input type = "hidden" name="questionAnswerID" value="02">
    <input type = "text" name = "comment" maxlength = "80" />
    <input type = "submit" name = "submit2" value = "投票" />

    </div>
    </form>
    <br>
    <br>
    <form method="POST" action="main.php" >
      <input type="submit" id="result" name="result" value="投票せずに結果を見る">
    </form>


    <h1>php-sdk</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>

  </body>
</html>
