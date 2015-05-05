<!DOCTYPE html>
<html>
<head>

<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
$SONGSTOPLAY = 4;

$id = htmlspecialchars($_GET['id']);
$ban = htmlspecialchars($_GET['ban']);
$edit = htmlspecialchars($_GET['edit']);
if ($edit == ""){
  $edit = 0;
}
$filename = "matches/".$id.".json";
//$file = fopen($filename, 'w') or die('Cannot open file:  '.$filename); 
$data = file_get_contents($filename);
$data = json_decode($data);

if ($data == null){
  echo "<br/><br/>No data found.";
  die();
}
$bannedcount = 0;
for($i=0; $i<count($data->songs); ++$i){
  if ($data->songs[$i][2]!=null){
    $bannedcount++;
  }
}
?>

  <title>ITG Eurocup 2015</title>
  <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <link href='style.css' rel='stylesheet' type='text/css'>
  <script>
    <?php if (!$data->ready){
      echo "var edit = ".$edit.";";
    }
    if ($bannedcount%2 != 0){
      if ($ban == "left"){ 
        $ban = "right";}
      else {
        $ban = "left";
      }
    }
    echo "var ban = '$ban';";?>
    var id = "<?php echo $id; ?>";

  </script>
</head>
<body>
<img src="logo.png" id="logo"/>

<table id="bantable">
  <tr>
    <th>Left</th>
    <th></th>
    <th class="matchnumber">Match #<?php echo $id; ?></th>
    <th></th>
    <th>Right</th>
  </tr>

<?php foreach ($data->songs as $song):?>
  <tr>
    <td>
      <?php if($song[2] == null){
        $class = "";
        if ($ban == "right" || $edit != 1){ $class = " inactive"; }
        echo "<div class='banbutton".$class."' data='left' data-song='$song[0]'>Ban &gt;</div>";
      } ?>
    </td>
    <td><div class="banmarker<?php if ($song[2] == "left"){ echo " active"; }?>"></div></td>
    <td>
      <div class='songdata<?php if ($song[2]) { echo " banned"; }
        echo "'><span class='song'>".$song[0]."</span><span class='number'>".$song[1]."</span>";?>
      </div>
    </td>
    <td><div class="banmarker<?php if ($song[2] == "right"){ echo " active"; }?>"></div></td>
    <td>
      <?php if($song[2] == null){
        $class = "";
        if ($ban == "left" || $edit != 1){ $class = " inactive"; }
        echo "<div class='banbutton".$class."' data='right' data-song='$song[0]'>&lt; Ban</div>";
      } ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>

<div id="progress">
  <?php

    for($i=1; $i<(count($data->songs)-($SONGSTOPLAY - 1)); ++$i){
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
    
    $('#reset').click(function(){
     if (edit == 1){
      var r = confirm("Are you sure?");
      if (r){
        resetPage();
      }
     }
    });

    $('.banbutton').click(function(){
     if (edit == 1 && $(this).attr('data') == ban){
      t = $(this); 
      banned = t.attr('data-song');
      $.ajax("change.php?id="+id+"&banned="+banned+"&banner="+ban)
      .done(function() {
        $('.banbutton').removeClass('inactive');
        if (t.attr('data') == "left"){
          t.parent().next().children().addClass('active');
          ban = "right";
          $("div[data='left']").addClass('inactive');
        }else{
          t.parent().prev().children().addClass('active'); 
          ban = "left";
          $("div[data='right']").addClass('inactive');
        }
        $("div[data-song='"+banned+"']").remove();
        //t.remove();
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
        var r = confirm("Are these your final choises? (Clicking cancel resets page.)");
        if (!r){ resetPage(); }
        else{
          window.location = "finalize.php?id=" + id;
        }
      }
    }
  });
})(jQuery);
</script>

<div id="copyt">Software by Codelio Oy</div>
</body>
</html>
