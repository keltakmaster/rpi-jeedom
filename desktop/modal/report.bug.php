<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (config::byKey('market::address') == '') {
	throw new Exception('{{Aucune adresse pour le market n\'est renseignée}}');
}
if (config::byKey('market::apikey') == '' && config::byKey('market::username') == '') {
	throw new Exception('{{Aucune clé pour le market n\'est renseignée. Veuillez vous enregistrer sur le market puis renseigner la clé dans Jeedom avant d\'ouvrir un ticket}}');
}
?>
<div id='div_alertReportBug'></div>
<form class="form-horizontal" role="form" id="form_reportBug">
    <div class="panel panel-success">
       <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-info"></i> {{Etape 1 : Information sur les tickets}}</h3></div>
       <div class="panel-body">
        {{Merci de vérifier avant toute ouverture de ticket :}}<br/>
        {{- que la question n'a pas déjà été posée sur le <a href='https://jeedom.com/forum'>forum</a>}}<br/>
        {{- que la catégorie est bien sélectionnée pour que votre ticket soit traité dans les plus courts délais}}<br/>
        {{- que la réponse n'est pas déjà dans la <a href='https://jeedom.github.io/documentation'>documentation</a>}}<br/>
        {{N'oubliez pas que poser la question sur le forum vous fournira généralement une réponse plus rapide que par ticket.}}
    </div>
</div>

<div class="panel panel-primary">
   <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cogs"></i> {{Etape 2 : Catégorie et type de la demande}}</h3></div>
   <div class="panel-body">
    <div class="form-group">
        <label class="col-sm-2 control-label">{{Type}}</label>
        <div class="col-sm-2">
            <select class="form-control ticketAttr" data-l1key="type">
                <option value=''>{{Aucun}}</option>
                <option value='question'>{{Question}}</option>
                <option value='incident'>{{Incident}}</option>
            </select>
        </div>
        <label class="col-sm-2 control-label">{{Catégorie}}</label>
        <div class="col-sm-4">
            <select class="form-control ticketAttr" data-l1key="category">
                <option value=''>{{Aucune}}</option>
                <option data-issue="" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/depannage.html">{{Général}}</option>
                <option data-issue="" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/scenario.html">{{Scénario}}</option>
                <?php
foreach (plugin::listPlugin(true) as $plugin) {
	echo '<option data-issue="' . $plugin->getIssue() . '" value="plugin::' . $plugin->getId() . '" data-pagehelp="' . $plugin->getDocumentation() . '">Plugin ' . $plugin->getName() . '</option>';
}
?>
           </select>
       </div>
   </div>

</div>
</div>

<div id="div_reportModalSearchAction" class="panel panel-primary" style="display:none;">
 <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-search"></i> {{Etape 3 : Chercher dans la documentation}}</h3></div>
 <div class="panel-body">
    <div class="form-group">
        <label class="col-sm-2 control-label">{{Rechercher}}</label>
        <div class="col-sm-2">
            <a class="btn btn-default" id="bt_searchOnFaq"><i class="fa fa-search"></i> {{Chercher}}</a>
        </div>
    </div>
</div>
</div>

<div class="panel panel-primary" id="div_reportModalSendAction" style="display:none;">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-pencil"></i> {{Etape 4 : Demande de support}}</h3></div>
    <div class="panel-body">
     <div class="form-group">
        <label class="col-sm-2 control-label">{{Titre}}</label>
        <div class="col-sm-7">
            <input class="form-control ticketAttr" data-l1key="title"/>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">{{Message}}</label>
        <div class="col-sm-9">
            <textarea class="form-control messageAttr input-sm" data-l1key="message" rows="8" ></textarea>
            <input class="form-control ticketAttr" data-l1key="options" data-l2key="page" style="display: none;"/>
        </div>
    </div>
    <div class="form-actions" style="height: 20px;">
        <a class="btn btn-success pull-right" id="bt_sendBugReport" style="color:white;"><i class="fa fa-check-circle"></i> {{Envoyer}}</a>
    </div>
</div>
</div>

<div class="panel panel-primary" id="div_reportModalPrivateIssue" style="display:none;">
    <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-pencil"></i> {{Etape 4 : Demande de support}}</h3></div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-sm-5 control-label">{{Ce plugin utilise un gestionnaire de demande de support}}</label>
            <div class="col-sm-2">
               <a class="btn btn-success" id="bt_reportBugIssueUrl" href="#" target="_blank" style="color:white;"><i class="fa fa-check-circle"></i> {{Accéder}}</a>
           </div>
       </div>
   </div>
</div>
</form>

<script>
    $('.ticketAttr[data-l1key=options][data-l2key=page]').value(location.href);

    $('#bt_sendBugReport').on('click', function () {
        var ticket = $('#form_reportBug').getValues('.ticketAttr')[0];
        ticket.messages = $('#form_reportBug').getValues('.messageAttr');
        $.ajax({
            type: "POST",
            url: "core/ajax/repo.ajax.php",
            data: {
                action: "sendReportBug",
                repo : 'market',
                ticket: json_encode(ticket),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertReportBug'));
            },
            success: function (data) {
                if (data.state != 'ok') {
                    $('#div_alertReportBug').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#bt_sendBugReport').hide();
                $('#div_alertReportBug').showAlert({message: '{{Votre ticket a bien été ouvert. Nous vous recontacterons prochainement.}}', level: 'success'});
            }
        });
    });

    $('#bt_searchOnFaq').on('click',function(){
        var issue = $('.ticketAttr[data-l1key=category] option:selected').attr('data-issue');
        if(issue != ''){
            $('#bt_reportBugIssueUrl').attr('href',issue);
            $('#div_reportModalPrivateIssue').show();
        }else{
            $('#div_reportModalSendAction').show();
        }
        window.open($('.ticketAttr[data-l1key=category] option:selected').attr('data-pagehelp'), '_blank');
    });

    $('.ticketAttr[data-l1key=type],.ticketAttr[data-l1key=category]').on('change',function(){
        if($('.ticketAttr[data-l1key=type]').value() != '' && $('.ticketAttr[data-l1key=category]').value() != ''){
            $('#div_reportModalSearchAction').show();
        }else{
            $('#div_reportModalSearchAction').hide();
        }
        $('#div_reportModalSendAction').hide();
    });
</script>
