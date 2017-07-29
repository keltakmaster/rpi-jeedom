  <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
 <style>
   .bs-sidenav .list-group-item{
    padding : 2px 2px 2px 2px;
  }
</style>
<div id="div_rowSystemCommand" class="row">
 <div class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
  <div class="bs-sidebar">
    <ul class="nav nav-list bs-sidenav list-group" id='ul_listSystemHistory'></ul>
    <ul class="nav nav-list bs-sidenav list-group">
      <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
      <?php if (jeedom::isCapable('sudo')) {?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo ../../health.sh">health.sh</a></li>
        <?php }?>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="dmesg">dmesg</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ifconfig">ifconfig</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="lsusb">lsusb</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ls -la /dev/ttyUSB*">ls -la /dev/ttyUSB*</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="free -m">free -m</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="ps ax">ps ax</a></li>
        <?php if (jeedom::isCapable('sudo')) {?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="sudo cat /var/log/mysql/error.log">MySQL log</a></li>
          <?php }?>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="cat /var/log/php5-fpm.log">PHP log</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="df -h">df -h</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="w">w</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="dpkg -l">dpkg -l</a></li>
          <li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="netstat -tupln"> netstat -tupln</a></li>
        </ul>
      </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8" style="border-left: solid 1px #EEE; padding-left: 25px;overflow-y:hidden;overflow-x:hidden;">

      <h3 id="h3_executeCommand">{{Cliquez sur une commande à droite ou tapez une commande personnalisée ci-dessous}}</h3>
      <input id="in_specificCommand" class="form-control" style="width:90%;display:inline-block;" /> <a id="bt_validateSpecifiCommand" class="btn btn-warning" style="position:relative;top:-2px;"><i class="fa fa-check"></i> {{OK}}</a>
      <pre id="pre_commandResult" style="width:100%;margin-top:5px;"></pre>
    </div>
  </div>

  <script>
   var hWindow = $('#div_rowSystemCommand').parent().outerHeight() - 30;
   $('#div_rowSystemCommand > div').height(hWindow);
   $('#pre_commandResult').height(hWindow - 120);
   $('.bt_systemCommand').off('click').on('click',function(){
    var command = $(this).attr('data-command');
    $('#pre_commandResult').empty();
    if($(this).parent().hasClass('list-group-item-danger')){
     bootbox.confirm('{{Etes-vous sûr de vouloir éxécuter cette commande : }}<strong>'+command+'</strong> ? {{Celle-ci est classé en dangereuse}}', function (result) {
      if (result) {
       jeedom.ssh({
        command : command,
        success : function(log){
         $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
         $('#pre_commandResult').append(log);
       }
     })
     }
   });
   }else{
     jeedom.ssh({
      command : command,
      success : function(log){
       $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
       $('#pre_commandResult').append(log);
     }
   })
   }
 });


   $('#ul_listSystemHistory').off('click','.bt_systemCommand').on('click','.bt_systemCommand',function(){
    var command = $(this).attr('data-command');
    $('#pre_commandResult').empty();
    $('#div_commandResult').empty();
    jeedom.ssh({
      command : command,
      success : function(log){
       $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
       $('#pre_commandResult').append(log);
     }
   })
  });

   $('#bt_validateSpecifiCommand').off('click').on('click',function(){
    var command = $('#in_specificCommand').value();
    $('#pre_commandResult').empty();
    jeedom.ssh({
      command : command,
      success : function(log){
        $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
        $('#pre_commandResult').append(log);
        $('#ul_listSystemHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="'+command+'">'+command+'</a></li>');
        var kids = $('#ul_listSystemHistory').children();
        if (kids.length >= 10) {
          kids.last().remove();
        }
      }
    })
  });

   $('#in_specificCommand').keypress(function(e) {
    if(e.which == 13) {
     var command = $('#in_specificCommand').value();
     $('#pre_commandResult').empty();
     jeedom.ssh({
      command : command,
      success : function(log){
        $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
        $('#pre_commandResult').append(log);
        $('#ul_listSystemHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="'+command+'">'+command+'</a></li>');
        var kids = $('#ul_listSystemHistory').children();
        if (kids.length >= 10) {
          kids.last().remove();
        }
      }
    })
   }
 });
</script>
