<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$id = htmlspecialchars($_GET['id']);
$banned = (string)htmlspecialchars($_GET['banned']);
$banner = (string)htmlspecialchars($_GET['banner']);
$json_data = file_get_contents('matches/'.$id.'.json');
$data = json_decode($json_data, true);

if ($data == null){
  http_response_code(500);
  die();
}

$i = 0;
while($i < count($data["songs"])){
  if ($data["songs"][$i][0] == $banned){
    break;
  }
  $i++;
}

if ($i == count($data["songs"])){
  http_response_code(500);
  die();
}

$data["songs"][$i][2] = $banner;

$filename = "matches/".$id.".json";
$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
fwrite($file, json_encode($data));
fclose($file);
