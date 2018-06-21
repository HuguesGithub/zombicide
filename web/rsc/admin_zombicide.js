$hj = jQuery;
$hj(document).ready(function(){
	if ( $hj('.tileRow .tile').length != 0 ) {
    addActionToMapEditorButtons();
	}
  if ( $hj('#list-table button').length != 0) {
    addActionToParamEditorButtons();
  }
});
function ajaxCall(data) {
  $hj.post(
    ajaxurl,
    data,
    function(response) {
      try {
        var obj = JSON.parse(response);
        return obj;
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
        return false;
      };
    }
  );
}
function addActionToParamEditorButtons() {
  $hj('#list-table button').unbind().click(function(){
    var type = $hj(this).data('type');
    if ( $hj(this).hasClass('editParam') ) {
      var id = $hj(this).data('id');
      if ( id == undefined ) {
        // On a un nouvel objet, il faut l'envoyer en base via Ajax.
        // Puis on doit mettre à jour la table...
	      $hj('#list-table tfoot button.btn-success').addClass('addParam').removeClass('editParam');
	      $hj('#list-table tfoot input').each(function(){ $hj(this).val(''); });
      } else {
        //***************/
        // Edition d'un Paramètre
        //***************/
				var data = {'action': 'dealWithAjax', 'ajaxAction': 'getParameter', 'type': type, 'id': id};
        $hj.post(
          ajaxurl,
          data,
          function(response) {
            try {
              var obj = JSON.parse(response);
              for ( p in obj ) {
                $hj('#'+type+'-'+p).val(obj[p]);
              }
              $hj('#list-table tfoot button.btn-success').removeClass('addParam').addClass('editParam');
            } catch (e) {
              console.log("error: "+e);
              console.log(response);
            };
          }
        );
      }
    } else if ( $hj(this).hasClass('cleanParam') ) {
      $hj('#list-table tfoot button.btn-success').addClass('addParam').removeClass('editParam');
      $hj('#list-table tfoot input').each(function(){ $hj(this).val(''); });
    } else if ( $hj(this).hasClass('addParam') ) {
        //***************/
        // Création d'un Paramètre
        //***************/
      	var inputs = '';
      	$hj('#list-table tfoot input').each(function(){
          if ( inputs != '' ) { inputs += '|'; }
          inputs += $hj(this).attr('id')+'='+$hj(this).val();
        });
				var data = {'action': 'dealWithAjax', 'ajaxAction': 'addParameter', 'type': type, 'inputs': inputs};
        $hj.post(
          ajaxurl,
          data,
          function(response) {
            try {
              console.log(response);
				      $hj('#list-table tfoot input').each(function(){ $hj(this).val(''); });
            } catch (e) {
              console.log("error: "+e);
              console.log(response);
            };
          }
        );
    } else if ( $hj(this).hasClass('rmvParam') ) {
      // On supprime un élément. Il faut l'envoyer en base via Ajax
      // Puis on doit mettre à jour la table.
      $hj(this).parent().parent().remove();
    }
    return false;
  });
}
function addActionToMapEditorButtons() {
	var width = $hj('.tileRow .tile').width();
	$hj('.tileRow .tile').each(function(e) {
		if ( !$hj(this).hasClass('firstRow') ) {
			$hj(this).css('height', width);
		}
	});
	$hj('.tileRow select').unbind().change(function(){
		var node = $hj(this);
		var value = $hj(this).val();
		var id = $hj(this).attr('id');
		var arrTmp = id.split('-');
		var arrCoords = arrTmp[0].split('_');
		var coordX = arrCoords[1];
		var coordY = arrCoords[2];
		var missionId = $hj('#id').val();
		var obj;
		var data = {'action': 'dealWithAjax', 'ajaxAction': 'updateMissionTile', 'coordX': coordX, 'coordY': coordY, 'missionId': missionId, 'value': value};
		$hj.post(
			ajaxurl,
			data,
			function(response) {
				try {
					var code = $hj('#'+id+' option:selected').text();
					console.log('all good : '+code);
					node.siblings('.thumbTile').attr('src', '/wp-content/plugins/zombicide/web/rsc/images/tiles/'+code+'-500px.png');
				} catch (e) {
					console.log("error: "+e);
					console.log(response);
				};
			}
		);
	});
	$hj('.tileRow button').unbind().click(function(){
		var dealAction = $hj(this).data('action');
		var ajaxAction = 'buildBlockTiles';
		var rkCol = $hj(this).data('col');
		var rkRow = $hj(this).data('row');
		var missionId = $hj('#id').val();
		var obj;
		var data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'dealAction': dealAction, 'rkCol': rkCol, 'rkRow': rkRow, 'missionId': missionId};
		$hj.post(
			ajaxurl,
			data,
			function(response) {
				try {
					obj = JSON.parse(response);
					if ( obj['mapEditor'] != '' ) {
						$hj('#mapEditor').html(obj['mapEditor']);
						addActionToMapEditorButtons();
					}
				} catch (e) {
					console.log("error: "+e);
					console.log(response);
				};
				return false;
			}
		);
	});
	$hj('.tileRow button.rdv').unbind().click(function(){
		var node = $hj(this);
		var dealAction = $hj(this).data('action');
		var ajaxAction = 'rotateMissionTile';
		var rkCol = $hj(this).data('col');
		var rkRow = $hj(this).data('row');
		var missionId = $hj('#id').val();
		var obj;
		var data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'orientation': dealAction, 'coordX': rkCol, 'coordY': rkRow, 'missionId': missionId};
		$hj.post(
			ajaxurl,
			data,
			function(response) {
				try {
					var classImg = 'north';
					switch ( dealAction ) {
						case 'E' : classImg = 'east'; break;
						case 'O' : classImg = 'west'; break;
						case 'S' : classImg = 'south'; break;
					}
					node.siblings('.thumbTile').removeClass('north south east west').addClass('thumbTile '+classImg);
					node.siblings('.rdv').removeClass('active');
					node.addClass('active');
				} catch (e) {
					console.log("error: "+e);
					console.log(response);
				};
				return false;
			}
	    );
	});
  $hj('.objectivesAndRules select').unbind().change(function() {
    var node = $hj(this);
    var type = '';
    var textarea = '';
    switch ( $hj(this).attr('id') ) {
      case 'idruleId' : type = 'rule'; textarea=type; break;
      case 'idsettingId' : type = 'rule'; textarea='setting'; break;
      case 'idobjectiveId' : type = 'objective'; textarea=type; break;
    }
		var obj;
		var data = {'action': 'dealWithAjax', 'ajaxAction': 'getObjRuleDescription', 'type': type, 'id': $hj(this).val()};
		$hj.post(
			ajaxurl,
			data,
			function(response) {
				try {
          $hj('#'+textarea+'-description').val(response);
				} catch (e) {
					console.log("error: "+e);
					console.log(response);
				};
				return false;
			}
		);
	});
  $hj('.objectivesAndRules button').unbind().click(function() {
		var obj;
		var data = '';
    var type = $hj(this).data('type');
    var id = $hj(this).data('id');
    if ( id==undefined ) {
      var title = $hj('#'+type+'-title').val();
      var description = $hj('#'+type+'-description').val();
      var selId = $hj('#id'+type+'Id').val();
      var missionId = $hj(this).data('missionid');
      data = {'action': 'dealWithAjax', 'ajaxAction': 'addMissionObjRule', 'type': type, 'title': title, 'description': description, 'selId': selId, 'missionId': missionId};
    } else {
      data = {'action': 'dealWithAjax', 'ajaxAction': 'rmwMissionObjRule', 'type': type, 'id': id};
    }
		$hj.post(
			ajaxurl,
			data,
			function(response) {
				try {
          switch ( type ) {
            case 'rule' : $hj('#ulAdminRules').html(response); break;
            case 'setting' : $hj('#ulAdminSettings').html(response); break;
            case 'objective' : $hj('#ulAdminObjectives').html(response); break;
          }
        	addActionToMapEditorButtons();
				} catch (e) {
					console.log("error: "+e);
					console.log(response);
				};
			}
    );
		return false;
  });
}
