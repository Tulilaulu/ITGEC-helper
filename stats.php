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

echo "<h2>Statistics</h2><div id='banlist' style='text-align: left; width: 600px; position: relative; margin: 10px auto;'><h4>Ban and pick stats, order by ban</h4><br/>";
$filenames = [];
foreach ($files as $file) {
  if ($file != "." && $file != ".."){
    array_push($filenames,  explode(".", $file)[0]);
  }
}
natsort($filenames);
$banned = [low => [], high => [], d => []];
$picked = [low => array(), high => array(), d => array()];
$randomed = [low => array(), high => array(), d => array()];
foreach ($filenames as $id) {
$filename = "matches/".$id.".json";
$data = file_get_contents($filename);
$data = json_decode($data);
if ($data == null){
  continue;
}
$id = intval($id);
if (($id > 12 && $id < 46) || ($id > 82 && $id < 113)){
  $picked['low'][$data->songs[0][0]]++;
  $picked['low'][$data->songs[1][0]]++;
  foreach($data->songs as $song){
    $randomed['low'][$song[0]]++;
    if ($song[2] != NULL){
      $banned['low'][$song[0]]++;
    }
  }
}
if (($id > 45 || $id < 64) || ($id > 78 || $id < 83)){
  $picked['high'][$data->songs[0][0]]++;
  $picked['high'][$data->songs[1][0]]++;
  foreach($data->songs as $song){
    $randomed['high'][$song[0]]++;
    if ($song[2] != NULL){
      $banned['high'][$song[0]]++;
    }
  }
}
if ($id < 13){
  $picked['d'][$data->songs[0][0]]++;
  $picked['d'][$data->songs[1][0]]++;
  foreach($data->songs as $song){
    $randomed['d'][$song[0]]++;
    if ($song[2] != NULL){
      $banned['d'][$song[0]]++;
    }
  }
}
}
echo "<h2>Low</h2>";
natsort($banned['low']);
$banned['low'] = array_reverse($banned['low']);
foreach($banned['low'] as $ban => $num){
  echo "$ban was banned $num time(s) and picked ";
  if (!array_key_exists($ban, $picked['low'])){
    echo "0";
  }else{
    echo $picked['low'][$ban];
  }
  echo " out of ".$randomed['low'][$ban]."<br/>";
}
echo "<h4>Picks only</h4><br/>";
natsort($picked['low']);
$picked['low'] = array_reverse($picked['low']);
foreach($picked['low'] as $pick => $num){
  echo "$pick was picked $num time(s) out of ".$randomed['low'][$pick]."<br/>";
}

echo "<h2>High</h2>";
natsort($banned['high']);
$banned['high'] = array_reverse($banned['high']);
foreach($banned['high'] as $ban => $num){
  echo "$ban was banned $num time(s) and picked ";
  if (!array_key_exists($ban, $picked['high'])){
    echo "0";
  }else{
    echo $picked['high'][$ban];
  }
  echo " out of ".$randomed['high'][$ban]."<br/>";
}
echo "<h4>Picks only</h4><br/>";
natsort($picked['high']);
$picked['high'] = array_reverse($picked['high']);
foreach($picked['high'] as $pick => $num){
  echo "$pick was picked $num time(s) out of ".$randomed['high'][$pick]."<br/>";
}


echo "<h2>Double</h2>";
natsort($banned['d']);
$banned['d'] = array_reverse($banned['d']);
foreach($banned['d'] as $ban => $num){
  echo "$ban was banned $num time(s) and picked ";
  if (!array_key_exists($ban, $picked['d'])){
    echo "0";
  }else{
    echo $picked['d'][$ban];
  }
  echo " out of ".$randomed['d'][$ban]."<br/>";
}
echo "<h4>Picks only</h4><br/>";
natsort($picked['d']);
$picked['d'] = array_reverse($picked['d']);
foreach($picked['d'] as $pick => $num){
  echo "$pick was picked $num time(s) out of ".$randomed['d'][$pick]."<br/>";
}
?>
</div>

<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
