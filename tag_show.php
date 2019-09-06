<!DOCTYPE html>
<html lang = "ja">

<head>
    <meta charset = "UFT-8">
    <title>YouTube Metatag Viewer</title>
    <!-- CSS -->
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css"> -->
	<link rel="stylesheet" href="style.css">
	<!-- <link rel="styleshhet" href="css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body style='background-image: url("youtube_located_white.png");'>

<nav class="navbar navbar-expand-sm navbar-light bg-dark mt-3 mb-3" style="margin-top:0!important;">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav4" aria-controls="navbarNav4" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
		<a class="navbar-brand" style="font-weight:bold;">YouTube Metatag Viewer</a>
		<div class="collapse navbar-collapse justify-content-center">
        <form class="form-inline" action="tag_show.php" method="get">
    		<input id="sbox5" type="text" name="url" placeholder="YouTube URL" style="width:600px">
			<button id="sbtn5" type="submit">check!</button>
        </form>
    </nav> 


<!-- -------------------------------------- -->
<?php

#関数読み込み
require('func.php');

#URLからhtml取得
if(isset($_GET["url"])){
    $url = $_GET["url"];
} else {
    echo "</body>";
    exit();
}
$tag = createvideotag($url);
echo '<br><div style="display:flex;justify-content:center;"><center>' . $tag . "</center>";
//$page_contents = file_get_contents($url);
$page_contents = file_cget_contents($url);

#keywordを検索
preg_match('/keywords\\\":\[(.+?)\]/', $page_contents, $match_keyword);
if(strlen($match_keyword[0]) == 0) {
    echo "メタタグ無し";
    echo "</body>";
    exit();
}

echo '<div style="margin-left:35px;">この動画のメタタグは...<br><ul style="background:white; opacity:0.8;">';

#keywordを順番にecho
for ($i = 0; $i < 100; $i++) { 
    preg_match('/\\\"(.+?)\\\"/', $match_keyword[1], $keyword);
    echo '<li><a href="https://www.youtube.com/results?search_query=' . $keyword[1] . '" target="_blank">' . $keyword[1] . '</a></li>';
    $match_keyword[1] = removeStr($match_keyword[1], array($keyword[0], ","));
    if(strlen($match_keyword[1]) == 0) {
        echo "</ul>";
        break;
    }
}
echo "</div></div>"

?>
<!-- -------------------------------------- -->
</body>
<?php exit(); ?>
