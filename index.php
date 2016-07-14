<!DOCTYPE html>
<html>
<head>
  <title>ITG Eurocup 2016</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
  <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>

<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$dir    = 'matches/';
$files = scandir($dir);

  echo "<img id='logo' src='logo.png'/><br/><br/>";

echo "<div id='matchlist'>";
$filenames = [];
foreach ($files as $file) {
  if ($file != "." && $file != ".."){
    array_push($filenames,  explode(".", $file)[0]);
  }
}
natsort($filenames);
foreach ($filenames as $id) {
    echo "Match #".$id.": <a href='ban.php?id=$id'>Ban stage</a> | <a href='match.php?id=$id'>Songs in final order</a><br/>";
}
?>
</div>
<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
