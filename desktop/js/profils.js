
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

 jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveProfils").click();
});

 $('#bt_configureTwoFactorAuthentification').on('click',function(){
     var profil = $('#div_pageContainer').getValues('.userAttr')[0];
    $('#md_modal').dialog({title: "{{Authentification 2 étapes}}"});
    $("#md_modal").load('index.php?v=d&modal=twoFactor.authentification').dialog('open');
});

 $("#bt_saveProfils").on('click', function (event) {
    $.hideAlert();
    var profil = $('#div_pageContainer').getValues('.userAttr')[0];
    if (profil.password != $('#in_passwordCheck').value()) {
        $('#div_alert').showAlert({message: "{{Les deux mots de passe ne sont pas identiques}}", level: 'danger'});
        return;
    }
    jeedom.user.saveProfils({
        profils: profil,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: "{{Sauvegarde effectuée}}", level: 'success'});
            jeedom.user.get({
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#div_pageContainer').setValues(data, '.userAttr');
                    modifyWithoutSave = false;
                }
            });
        }
    });
    return false;
});

$('#bt_genUserKeyAPI').on('click',function(){
    var profil = $('#div_pageContainer').getValues('.userAttr')[0];
    profil.hash = '';
    jeedom.user.saveProfils({
        profils: profil,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: "{{Opération effectuée}}", level: 'success'});
            jeedom.user.get({
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#div_pageContainer').setValues(data, '.userAttr');
                    modifyWithoutSave = false;
                }
            });
        }
    });
});

$('.userAttr[data-l1key=options][data-l2key=bootstrap_theme]').on('change', function () {
    if($(this).value() == ''){
        $('#div_imgThemeDesktop').html('<img src="core/img/theme_default.png" height="300" class="img-thumbnail" />');
    }else{
        $('#div_imgThemeDesktop').html('<img src="core/themes/' + $(this).value() + '/desktop/preview.png" height="300" class="img-thumbnail" />');
    }
    
});

jeedom.user.get({
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#div_pageContainer').setValues(data, '.userAttr');
        $('#in_passwordCheck').value(data.password);
        modifyWithoutSave = false;
    }
});

$('#div_pageContainer').delegate('.userAttr', 'change', function () {
    modifyWithoutSave = true;
});

 $('.bt_selectWarnMeCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('.userAttr[data-l1key="options"][data-l2key="notification::cmd"]').value(result.human);
    });
});


