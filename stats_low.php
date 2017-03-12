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
$banned = [];
$picked = [];
$randomed = [];

foreach ($filenames as $id) {
$filename = "matches/".$id.".json";
$data = file_get_contents($filename);
$data = json_decode($data);
if ($data == null){
  continue;
}
if (($id > 12 && $id < 46) || ($id > 82 && $id < 113)){
$picked[$data->songs[0][0]]++;
$picked[$data->songs[1][0]]++;
foreach($data->songs as $song){
  $randomed[$song[0]]++;
if ($song[2] != NULL){
  $banned[$song[0]]++;
}
}
}
}
natsort($banned);
$banned = array_reverse($banned);
foreach($banned as $ban => $num){
  echo "$ban was banned $num time(s) and picked ";
  if (!array_key_exists($ban, $picked)){
    echo "0";
  }else{
    echo $picked[$ban];
  }
  echo " out of ".$randomed[$ban]."<br/>";
}
echo "<h4>Picks only</h4><br/>";
natsort($picked);
$picked = array_reverse($picked);
foreach($picked as $pick => $num){
  echo "$pick was picked $num time(s) out of ".$randomed[$pick]."<br/>";
}
?>
</div>

<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
