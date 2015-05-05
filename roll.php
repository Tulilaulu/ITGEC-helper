<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$disc = htmlspecialchars($_GET['disc']);
$ban = htmlspecialchars($_GET['ban']);
$dif = htmlspecialchars($_GET['dif']);
$dif = explode(",", $dif);
$json_data = file_get_contents('biisit.json');
$biisit = json_decode($json_data, true);

$pool = $biisit[$disc];
$selected = [];
foreach ($dif as $d){
  $current_pool = $pool[$d];
  shuffle($current_pool);
  $selected []= [$current_pool[0], $d, null];
  $selected []= [$current_pool[1], $d, null]; //last value = ban status
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
fwrite($file, json_encode(["songs" => $selected, "ready" => false]));
fclose($file);
header("Location: ban.php?id=".$i."&edit=1&ban=".$ban);
