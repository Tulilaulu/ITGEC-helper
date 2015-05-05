<!DOCTYPE html>
<html>
<head>
<title>ITG EC 2015</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
<script>
/*
ITG EC Tournament matchup helper software. 
Made by Aurora Tulilaulu of Codelio Oy
*/
(function($){
  $(function(){
    $('.discipline, .dif, .ban').click(function(){
      if($(this).hasClass('discipline')){
        $('.discipline').removeClass('selected');
      }
      if($(this).hasClass('ban')){
        $('.ban').removeClass('selected');
      }
      $(this).toggleClass('selected');
    });
    $('#start').click(function(){
      var parameters = "";
      if ($('.discipline.selected').length < 1 || $('.ban.selected').length < 1 || $('.dif.selected').length < 1){
        $('#error').text("All parameters must be selected");
        return;    
      }
      parameters = "?disc="+$('.discipline.selected').attr('id');
      parameters = parameters+"&ban="+$('.ban.selected').attr('id');
      parameters = parameters+"&dif=";
      var error = false;
      $('.dif.selected').each(function(index, x){
        var num =  $(x).attr('data');
        if (($('.discipline.selected').attr('id') == 'low' && ['9', '10', '11', '12'].indexOf(num) == -1)
        ||($('.discipline.selected').attr('id') == 'high' && ['13', '14', '15', '16'].indexOf(num) == -1)
        ||($('.discipline.selected').attr('id') == 'double' && ['9', '10', '11', '12', '13'].indexOf(num) == -1)){
          $('#error').text("Discipline/Difficulty mismatch");
          error = true;
        }
        parameters += num+",";
      })
      if (error){ return; }
      parameters = parameters.substring(0, parameters.length - 1);
      window.location = "roll.php" + parameters;
      return;
    });
  });
})(jQuery);
</script>
<img id='logo' src='logo.png'/><br/>

<p id="error"></p>

<table id="options"><tr>
  <td>Discipline</td>
  <td><p id="low" class="discipline">Single Low</p>
      <p id="high" class="discipline">Single High</p>
      <p id="double" class="discipline">Double</p>
  </td>
</tr>
<tr>
  <td>Difficulties</td>
  <td>
    <p data="9" class="dif">9</p>
    <p data="10" class="dif">10</p>
    <p data="11" class="dif">11</p>
    <p data="12" class="dif">12</p>
    <p data="13" class="dif">13</p>
    <p data="14" class="dif">14</p>
    <p data="15" class="dif">15</p>
    <p data="16" class="dif">16</p>
  </td>
</tr>
<tr>
  <td>Who bans first?</td>
  <td>
    <p class="ban" id="left">Left first</p>
    <p class="ban" id="right">Right first</p>
  </td>
</tr></table>


<div id="start">Start &gt;</div>

</body>
</html>
