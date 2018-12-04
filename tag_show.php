<!DOCTYPE html>
<html lang = "ja">

<head>
    <meta charset = "UFT-8">
    <title>youtube_metatag_get</title>
    <!-- CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css"> -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
<center>
<form id="form5" action="tag_show.php" method="get">
    <input id="sbox5" type="text" name="url" placeholder="Youtube URL">
	<button id="sbtn5" type="submit">check!</button>
</form>
</center>

<!-- -------------------------------------- -->
<?php

#関数読み込み
require('func.php');

#URLからhtml取得
if(isset($_GET["url"])){
    $url = $_GET["url"];
} else {
    exit;
}
$tag = createvideotag($url);
echo "<br><center>" . $tag . "</center><br>";
//$page_contents = file_get_contents($url);
$page_contents = file_cget_contents($url);

#keywordを検索
preg_match('/keywords\\\":\[(.+?)\]/', $page_contents, $match_keyword);
if(strlen($match_keyword[0]) == 0) {
    echo "メタタグ無し";
    exit();
}

echo "この動画のメタタグは...<br><ul>";

#keywordを順番にecho
for ($i = 0; $i < 100; $i++) { 
    preg_match('/\\\"(.+?)\\\"/', $match_keyword[1], $keyword);
    echo "<li>" . $keyword[1] . "</li>";
    $match_keyword[1] = removeStr($match_keyword[1], array($keyword[0], ","));
    if(strlen($match_keyword[1]) == 0) {
        echo "</ul>";
        break;
    }
}

?>
<!-- -------------------------------------- -->
</body>
<?php exit(); ?>