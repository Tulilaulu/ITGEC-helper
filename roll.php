<?php 
include('config.php');
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$first = json_decode(file_get_contents($url.'players/'.$_GET['first']));
$second = json_decode(file_get_contents($url.'players/'.$_GET['second']));
$match = $_GET['match'];
$round = $_GET['round'];
$type = htmlspecialchars($_GET['type']);
$songs = json_decode(file_get_contents($url.'rounds/'.$round.'/get-round-stepcharts'));
$songs = $songs->stepCharts;
//$match = file_get_contents($url.'matches/'.$match);
//var_dump($match);

$selected = [];
$pool = [];
$difs = [];
foreach ($songs as $song){//organize songs
  if (!isset($pool['lvl'.$song->difficultyLevel])){
    $pool['lvl'.$song->difficultyLevel] = [];
  }
  if (!in_array('lvl'.$song->difficultyLevel, $difs)){
    array_push($difs, 'lvl'.$song->difficultyLevel);
  }
  array_push($pool['lvl'.$song->difficultyLevel], $song);
}
$songs_per_dif = 3;
foreach ($difs as $d){//pick songs
    $current_pool = $pool[$d];
    shuffle($current_pool);
    for ($i = 0; $i < $songs_per_dif; $i++){
      $selected []= [$current_pool[$i], $current_pool[$i]->difficultyLevel, null];
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
fwrite($file, json_encode(["songs" => $selected, "ready" => false, "first" => $first, "second" => $seconf, "match" => $match, "round" => $round, "type" => $type]));
fclose($file);
header("Location: ban.php?id=".$i."&edit=1");
