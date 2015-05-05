<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

$id = htmlspecialchars($_GET['id']);
$json_data = file_get_contents('matches/'.$id.'.json');
$data = json_decode($json_data, true);

if ($data == null){
  echo "An error occured.";
  die();
}

$data["ready"] = true;
shuffle($data["songs"]);
$filename = "matches/".$id.".json";
$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
fwrite($file, json_encode($data));
fclose($file);
header("Location: match.php?id=".$id);
