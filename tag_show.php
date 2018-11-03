<!DOCTYPE html>
<html lang = “ja”>

<head>
<meta charset = “UFT-8”>
<title>youtube_metatag_get</title>
</head>

<form action="tag_show.php" method="get">
    <input type="text" name="url" placeholder="Youtube URL">
	<button type="submit">check!</button>
</form>

<?php
function removeStr($data, $remove_array){
    foreach($remove_array as $remove_data){
        $data = str_replace($remove_data, "", $data);
    }
    return $data;
}

#URLからhtml取得
$url = $_GET["url"];
$cmd = "curl " . $url . " > page.html";
exec($cmd);
$page_contents = file_get_contents("page.html");

#keywordを検索
preg_match('/keywords\\\":\[(.+?)\]/', $page_contents, $match_keyword);
if(strlen($match_keyword[0]) == 0) {
    echo "No meta tag";
    exit();
}

#keywordを順番にecho
for ($i = 0; $i < 100; $i++) { 
    preg_match('/\\\"(.+?)\\\"/', $match_keyword[1], $keyword);
    echo $keyword[1] . "<br>";
    $match_keyword[1] = removeStr($match_keyword[1], array($keyword[0], ","));
    if(strlen($match_keyword[1]) == 0) break;
}

exit();
?>