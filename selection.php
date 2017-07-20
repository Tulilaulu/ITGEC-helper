<?php 
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

$id = htmlspecialchars($_GET['id']);
$json_data = file_get_contents('matches/'.$id.'.json');
$data = json_decode($json_data, true);

if ($data == null){
  echo "An error occured.";
  die();
}

echo "<script>var id=".$id.";</script>";

?>
<html>
<head><title>ITG EC 2017</title></head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
  <link href='style.css' rel='stylesheet' type='text/css'>

<body>
<img src="logo.png" id="logo"><br/>
<table id="bantable">
  <tr>
    <td><p class="selection-p">Who picks first?</p></td>
    <td><div class="namebutton firstpicker" data="left"><?php echo $data['left'];?></div></td>
    <td><div class="namebutton firstpicker" data="right"><?php echo $data['right'];?></div></td>
  </tr>
  <tr>
    <td><p class="selection-p">Who's song is played first?</p></td>
    <td><div class="namebutton firstplayer" data="left"><?php echo $data['left'];?></div></td>
    <td><div class="namebutton firstplayer" data="right"><?php echo $data['right'];?></div></td>
  </tr>
</table>

<table id="bantable">
  <tr>
    <th><?php echo $data['left']; ?></th>
    <th></th>
    <th><?php echo $data['right']; ?></th>
  </tr>
<?php foreach ($data['songs'] as $song):?>
  <?php if ($song[2] == null): ?>
  <tr>
    <td>
      <?php if($song[2] == null){
        echo "<div class='banbutton inactive' data='left' data-song=\"$song[0]\" data-dif='$song[1]'>Pick &gt;</div>";
      } ?>
    </td>
    <td>
      <div class='songdata<?php if ($song[2]) { echo " banned"; }?>'>
      <?php
        echo "<span class='song'>".$song[0]."</span><span class='number'>".$song[1]."</span>";
      ?>
      </div>
    </td>
    <td>
      <?php if($song[2] == null){
        echo "<div class='banbutton inactive' data='right' data-song=\"$song[0]\" data-dif='$song[1]'>&lt; Pick</div>";
      } ?>
    </td>
  </tr>
  <?php endif; ?>
<?php endforeach; ?>
</table>

<script>
(function($){
  $(function(){
    var firstpicker = null;
    var firstplayer = null;
    var isPicked = false;
    var pick1 = "";
    var pick2 = "";
    $(".namebutton").click(function(){
      if ($(this).hasClass('firstpicker')){
        if (isPicked == false){
          $('.firstpicker').removeClass('selected');
          firstpicker = $(this).attr('data');
          $('.banbutton').addClass('inactive'); 
          $('.banbutton[data='+firstpicker+']').removeClass('inactive');
          $(this).addClass('selected');
        }
      }
      if ($(this).hasClass('firstplayer')){
        $('.firstplayer').removeClass('selected');
        firstplayer = $(this).attr('data');
        $(this).addClass('selected');
      }
    });
    $('.banbutton').click(function(){
      if (firstpicker == null || firstplayer == null){
        alert("Answer the first two questions before picking songs");
      }else{
        $('.banbutton[data='+$(this).attr('data')+']').removeClass('selected');
        $(this).addClass('selected');
        isPicked = true;
        $('.firstpicker').css('cursor', 'not-allowed');
        $('.banbutton').addClass('inactive');
        if (pick1 == ""){
          pick1 = $(this).attr('data-song');
          if (firstpicker == 'left'){ 
            $('.banbutton[data=right]').removeClass('inactive');
            $(".banbutton[data=right][data-song='"+$(this).attr('data-song')+"']").hide();
          }
          if (firstpicker == 'right'){
            $('.banbutton[data=left]').removeClass('inactive');
            $(".banbutton[data=left][data-song='"+$(this).attr('data-song')+"']").hide();
          }
        } else{
          $('.banbutton').addClass('inactive');
          pick2 = $(this).attr('data-song');
          var r = confirm("Are these your final choises?");
          if (r == true && firstpicker != null && firstplayer != null){
            //find out which is the first song to play
            if ((firstplayer == 'left' && firstpicker == 'left') || (firstplayer == 'right' && firstpicker == 'right')){
              window.location = "finalize.php?id="+id+"&pick1="+pick1+"&pick2="+pick2+"&firstplayer="+firstplayer+"&firstpicker="+firstpicker;
            }else if ((firstplayer == 'right' && firstpicker == 'left') || (firstplayer == 'left' && firstpicker == 'right')){
              window.location = "finalize.php?id="+id+"&pick1="+pick2+"&pick2="+pick1+"&firstplayer="+firstplayer+"&firstpicker="+firstpicker;;
            }
            else{
              alert("error");
            }
          }
        }
      }
    });

  });
})(jQuery);
</script>

<div id="copyt">Software by Codelio Oy</div>

</body>
</html>
