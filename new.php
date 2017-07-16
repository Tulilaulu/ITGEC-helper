<!DOCTYPE html>
<?php include('config.php');?>
<html>
<head>
<title>ITG EC 2015</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
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
    <?php echo "var url = '".$url."';"; ?>
    $.ajax(url+'tournament-events', {success: processEvents});
    var matches = null;
    var selectedmatch = null;

    function processEvents(data) {
      var events = [];
      _.each(data, function(e){
        if (e['status'] == "Ongoing"){
          events.push(e);
          $('#event').append("<option value='"+e._id+"'>"+e.name+"</option>");
        }
      });
      console.log("events", events);
      getMatches();
      $('#event').change(getMatches);
      $('#match').change(setMatchdata);
    }

    function getMatches(){
      $.ajax(url+'tournament-events/'+$('#event').val()+'/get-open-matches', {success: processMatches});   
    }

    function processMatches (data){
      matches = data.matches;
      $('#match').empty();
      _.each(data.matches, function(match){
        var playerdata = [];
        _.each(match.players, function(playerId){ 
          $.ajax(url+'players/'+playerId, {success: function(player){
            playerdata.push(player);
          }});
        });
        match.playerdata = playerdata;
        $(document).ajaxStop(function(){
          $('#match').append("<option id='"+match._id+"' value='"+match.roundId+"'>"+match.playerdata[0].nickname+" vs. "+match.playerdata[1].nickname+"</option>");
        });
      });
      $(document).ajaxStop(function(){ setMatchdata();});
    }

    function setMatchdata(){
      selectedmatch = _.find(matches, function(m){
        if (m._id == $('#match option:selected').attr('id')){
          return m;
        }
      });
      console.log("selected match", selectedmatch);
      $('#left').html(selectedmatch.playerdata[0].nickname);
      $('#left').attr('playerid', selectedmatch.playerdata[0]._id);
      $('#right').html(selectedmatch.playerdata[1].nickname);
      $('#right').attr('playerid', selectedmatch.playerdata[1]._id);
    }

    $('.ban').click(function(){
       $('.ban').removeClass('selected');
       $(this).toggleClass('selected');
    });

    $('#start').click(function(){
      var parameters = "";
      if ($('.ban.selected').length < 1 && $('#event') && $('#match')){
        $('#error').text("All parameters must be selected");
        return;    
      }
      parameters = "first="+$('.ban.selected').attr('playerid');
      parameters += "&second="+$('.ban').not('.selected').attr('playerid');
      parameters += "&match="+selectedmatch._id;
      parameters += "&round="+selectedmatch.roundId;
      parameters += "&type="+selectedmatch.bestOfCount;
      parameters += "&tournament="+selectedmatch.tournamentId;
      window.location = "roll.php?" + parameters;
      return;
    });
  });
})(jQuery);
</script>
<img id='logo' src='logo.png'/><br/>

<p id="error"></p>

<table id="options">
<tr>
  <td>Event</td>
  <td>
    <div class="selectwrapper">
     <select id="event"></select>
    </div>
  </td>
</tr>
<tr>
  <td>Match</td>
  <td>
    <div class="selectwrapper">
     <select id="match"></select>
    </div>
  </td>
</tr>
<tr id="bantr">
  <td>Who bans first?</td>
  <td>
    <p class="ban" id="left">Left first</p>
    <p class="ban" id="right">Right first</p>
  </td>
</tr>
</table>


<div id="start">Start &gt;</div>

</body>
</html>
