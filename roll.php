<?php 
include('config.php');
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$first = json_decode(file_get_contents($url.'players/'.$_GET['first']))->nickname;
$second = json_decode(file_get_contents($url.'players/'.$_GET['second']))->nickname;
$match = $_GET['match'];
$round = $_GET['round'];
$type = htmlspecialchars($_GET['type']);
$tournament = htmlspecialchars($_GET['tournament']);
$songs = json_decode(file_get_contents($url.'rounds/'.$round.'/get-round-stepcharts'));
$songs = $songs->stepCharts;
//$match = file_get_contents($url.'matches/'.$match);
//var_dump($match);

$selected = [];
$pool = [];
$difs = [];
foreach ($songs as $song){//organize songs
  if (!isset($pool[(string)$song->difficultyLevel])){
    $pool[(string)$song->difficultyLevel] = [];
  }
  if (!in_array((string)$song->difficultyLevel, $difs)){
    array_push($difs, (string)$song->difficultyLevel);
  }
  array_push($pool[(string)$song->difficultyLevel], $song);
}
if (count($songs) == 3){
  $songs_per_dif = 5;
}else{
  $songs_per_dif = 4;
}
foreach ($difs as $d){//pick songs
    $current_pool = $pool[$d];
    shuffle($current_pool);
    for ($i = 0; $i < $songs_per_dif; $i++){//process to previous format so that i dont have write the picking phase again
      $selected []= [$current_pool[$i]->song->title, (string)$current_pool[$i]->difficultyLevel, null, $current_pool[$i]->_id];
    }
}
$i = 1;
while (file_exists("matches/".$i.".json")){
  $i++;
}
if ($i > 999){
  die("Too many files, delete to make room");
}
$filename = "matches/".$i.".json";
$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
fwrite($file, json_encode(["songs" => $selected, "ready" => false, "left" => $first, "right" => $second, "match" => $match, "round" => $round, "type" => $type, "tournament" => $tournament]));
fclose($file);
header("Location: ban.php?id=".$i."&edit=1&ban=left");
