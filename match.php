<!DOCTYPE html>
<html>
<head>
  <title>ITG Eurocup 2015</title>
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
$id = htmlspecialchars($_GET['id']);
$filename = "matches/".$id.".json";
//$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
$data = file_get_contents($filename);
$data = json_decode($data);
echo "<img src='logo.png' id='logo'/>";
if ($data == null){
  echo "<br/><br/>No data found.";
  die();
}
if (!$data->ready){
  echo "<br/><br/>This match is still in the <a href='ban.php?id=$id'>banning phase</a>.";
  die();
}
?>

<br/>
<div class="finalsongdatacontainer">
<h4>Match #<?php echo $id;?></h4>
<h1>Songs to be played</h1>
<table>
<?php $i = 1;
foreach ($data->songs as $song){
  if ($song[2] == null){
      echo "<tr><td><span class='ordernumber'>$i.</span></td>";
      echo "<td><span class='fsong'>".$song[0]."</span></td><td><span class='fnumber'>".$song[1]."</span>";
      echo "</td></tr>";
      $i++;
    }
} ?>
</table>
</div>
<div class="bannedsongdatacontainer">
<h3>Banned songs</h3><table>
<?php
foreach ($data->songs as $song){
  if ($song[2] != null){
      echo "<tr><td><span class='song'>".$song[0]."</span></td>";
      echo "<td><span class='number'>".$song[1]."</span></td>";
      echo "<td><span class='banned'>(banned by ".$song[2].")</span></td></tr>";
    }
} ?>
</table>
</div>

<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
