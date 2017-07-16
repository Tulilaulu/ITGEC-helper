<!DOCTYPE html>
<html>
<head>

<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/

$id = htmlspecialchars($_GET['id']);
$ban = htmlspecialchars($_GET['ban']);
$edit = htmlspecialchars($_GET['edit']);
if ($edit == ""){
  $edit = 0;
}
$filename = "matches/".$id.".json";
//$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
$origdata = file_get_contents($filename);
$data = json_decode($origdata);

if ($data == null){
  echo "<br/><br/>No data found.";
  die();
}
$left = $data->left;
$right = $data->right;
$bannedcount = 0;
$bandif = $data->songs[0][1];
$difs = [];
for($i=0; $i<count($data->songs); ++$i){
  if (!in_array($data->songs[$i][1], $difs)){
    array_push($difs, $data->songs[$i][1]);
  }
  if ($data->songs[$i][2]!=null){
    $bannedcount++;
    if ($bannedcount % 2 == 0){
      $bandif++;
    }
  }
}
$songsToPlay = (count($data->songs)) - (2 * count($difs));
?>

  <title>ITG Eurocup 2017</title>
  <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <link href='style.css' rel='stylesheet' type='text/css'>
  <script>
    <?php if (!$data->ready){
      echo "var edit = '$edit';";
    }
    if (in_array($bannedcount, [1, 2, 5, 6])){
      if ($ban == "left"){ 
        $ban = "right";}
      else {
        $ban = "left";
      }
    } else{
      if ($ban == "left"){ 
        $ban = "left";}
      else {
        $ban = "right";
      }
    }
    echo "var bannedcount = $bannedcount;";
    echo "var ban = '$ban';";
    echo "var id = '$id';";
    echo "var songstoplay = '$songsToPlay';";
    echo "var bandif = ".$bandif.";";
?>
  var songdata = <?php echo $origdata; ?>;
  </script>
</head>
<body>
<img src="logo.png" id="logo"/>

<table id="bantable">
  <tr>
    <th><?php echo $left; ?></th>
    <th></th>
    <th class="matchnumber">Match #<?php echo $id; ?></th>
    <th></th>
    <th><?php echo $right; ?></th>
  </tr>

<?php foreach ($data->songs as $song):?>
  <tr class="dif<?php echo $song[1];?>">
    <td>
      <?php if($song[2] == null){
        $class = "";
        if ($ban == "right" || $edit != 1 || $bandif != $song[1]){ $class = " inactive"; }
        echo "<div class='banbutton".$class."' data='left' data-song=\"$song[0]\" data-dif='$song[1]'>Ban &gt;</div>";
      } ?>
    </td>
    <td><div class="banmarker<?php if ($song[2] == "left"){ echo " active"; }?>"></div></td>
    <td>
      <div class='songdata<?php if ($song[2]) { echo " banned"; }?>'>
      <?php
        echo "<span class='song'>".$song[0]."</span><span class='number'>".$song[1]."</span>";
      ?>
      </div>
    </td>
    <td><div class="banmarker<?php if ($song[2] == "right"){ echo " active"; }?>"></div></td>
    <td>
      <?php if($song[2] == null){
        $class = "";
        if ($ban == "left" || $edit != 1 || $bandif != $song[1]){ $class = " inactive"; }
        echo "<div class='banbutton".$class."' data='right' data-song=\"$song[0]\" data-dif='$song[1]'>&lt; Ban</div>";
      } ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>

<div id="progress">
  <?php
    for($i=1; $i<(count($data->songs)-($songsToPlay - 1)); ++$i){
      if ($bannedcount>=$i){
        echo "<span class='picked'>$i</span>";
      }else{
        echo "<span>$i</span>";
      }
    }
  ?>
</div>
<br/>
<div id="reset">Reset</div>

<script>
(function($){
  $(function(){
    var leftbans = [];
    var rightbans = [];
    songdata.songs.forEach(function(entry){ //this is only so that a page refresh wont mess everything up...
      if (entry[2] == "left"){
        leftbans.push(entry[1]);
      }
      if (entry[2] == "right"){
        rightbans.push(entry[1]);
      }
    });

    $('#reset').click(function(){
     if (edit == 1){
      var r = confirm("Are you sure?");
      if (r){
        resetPage();
      }
     }
    });

    function arraycount (array, needle){
      var count = 0;
      array.forEach(function(entry){
        if (entry == needle){
          count = count + 1;
        }
      });
      return count;
    }

    $('.banbutton').click(function(){
     if (edit == 1 && $(this).attr('data') == ban){
      t = $(this); 
      var p = t.parent();
      banned = t.attr('data-song');
      dif = t.attr('data-dif');
      //to check that no player bans too many of the same block
      if (ban == "right"){ 
        var c = arraycount(rightbans, dif);
        if ((songstoplay == 4 && c > 0) || (songstoplay != 4 && c > 1)){
            alert("Invalid pick");
            return;
        }
      }else{
        var c = arraycount(leftbans, dif);
        if ((songstoplay == 4 && c > 0) || (songstoplay != 4 && c > 1)){
            alert("Invalid pick");
            return;
        }
      }
      $.ajax("change.php?id="+id+"&banned="+banned+"&banner="+ban)
      .done(function() {
        bannedcount = bannedcount + 1;
        if (bannedcount % 2 == 0){
          bandif = bandif + 1;
        }
        if (ban == "left"){
          leftbans.push(dif);
        } else {
          rightbans.push(dif);
        }
        if ([1,3,5,7].indexOf(bannedcount) != -1){ //See if banner will change
          if (ban == "left"){
            ban = "right";
          }else{
            ban = "left";
          }
        }
        $(".banbutton").addClass('inactive');
        $(".banbutton[data-dif="+bandif+"][data='"+ban+"']").addClass('active');
        $(".banbutton[data-dif="+bandif+"][data='"+ban+"']").removeClass('inactive');
        $("div[data-song=\""+banned+"\"]").remove();
        if (t.attr('data') == "left"){ 
          p.next().children().addClass('active');
        }else{
          p.prev().children().addClass('active'); 
        }
        increment();
      })
      .fail(function() {
        alert( "An error occured." );
      })

     }
    });
    
    function resetPage(){
     if (edit == 1){
      $.ajax("reset.php?id="+id)
      .done(function() {
        location.reload();
      })
      .fail(function() {
        alert( "An error occured." );
      })
     }
    }
    function increment (){
        $('#progress span:not(.picked):first').addClass('picked');
      if ($('#progress').children().length == $('.picked').length){
        var r = confirm("Are these your final choices? (Clicking cancel resets page.)");
        if (!r){ resetPage(); }
        else{
          window.location = "selection.php?id=" + id;
        }
      }
    }
  });
})(jQuery);
</script>

<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
