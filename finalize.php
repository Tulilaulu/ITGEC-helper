<?php 
include('config.php');
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
$id = htmlspecialchars($_GET['id']);
$pick1 = htmlspecialchars($_GET['pick1']);
$pick2 = htmlspecialchars($_GET['pick2']);
$firstpicker = htmlspecialchars($_GET['firstpicker']);
$firstplayer = htmlspecialchars($_GET['firstplayer']);
$json_data = file_get_contents('matches/'.$id.'.json');
$data = json_decode($json_data, true);

if ($data == null){
  echo "An error occured.";
  die();
}

$data["ready"] = true;
$extrasongs = [];
$bannedsongs = [];
$extrasongIds = [];
echo "<pre>";
foreach ($data['songs'] as $song){
  var_dump($song);
  if ($song[0] == $pick1){
    $pick1 = $song;
  }
  else if ($song[0] == $pick2){
    $pick2 = $song;
  }
  else if ($song[2] == null){ //is not banned
    $extrasongs[] = $song;
    $extrasongIds[] = $song[3];
  }
  else{
    $bannedsongs[] = $song;
  }
}

shuffle($extrasongs);

$data['songs'] = array_merge([$pick1], [$pick2], $extrasongs, $bannedsongs);
if ($firstplayer == 'left'){
    $data['firstSongPickedBy'] = $data['left'];
}else{
     $data['firstSongPickedBy'] = $data['right'];
}
$filename = "matches/".$id.".json";

$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
fwrite($file, json_encode($data));
fclose($file);

//echo "<br/><br>";
//var_dump($extrasongs);
//echo "<br><br>";
//var_dump($pick1, $pick2);

//send results to api
$json =  ["stepChartIds" => [], "appId" => $appId, "tournamentId"=> $data['tournament']];
$json['stepChartIds'] = array_merge([$pick1[3]], [$pick2[3]],$extrasongIds);
$json = json_encode($json);
//var_dump($json);
$options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => $json ,
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents( $url.'matches/'.$data['match'].'/post-song-picks', false, $context );
$response = json_decode( $result );
//echo "context<br/>";
//var_dump($context);
//echo "<br/> options<br/>";
//var_dump($options);
//echo "<br/> json<br/>";
//var_dump($json);
//echo "<br/> response<br/>";
//var_dump($response);
//echo "<br/> url<br/>";
//var_dump($url.'matches/'.$data['match'].'/post-song-picks');
//die();
header("Location: match.php?id=".$id);
echo "<br/><br/><a style='font-size: 20px;' href='match.php?id=$id'>Click here to continue</a>";
