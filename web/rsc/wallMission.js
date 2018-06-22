var $hj = jQuery.noConflict();
$hj( document ).ready(function() {
	setWorkZoneHeight();
  setButtonAction();
  setCanvasAction();
	$hj(window).resize(function() {
		setWorkZoneHeight();
	});
  
  $hj('ul.equipments').sortable({
  	start: function( event, ui ) {
//      console.log(ui.item.data('elid'));
  	},
  	stop: function( event, ui ) {
  	}
  });
  $hj('ul.equipments').disableSelection();  
});

function setCanvasAction() {
	var isDown = false;
	var x1, y1;
	var canvas = $hj('#mapInterfaceBackground')[0];
	$hj('#mapInterfaceBackground').on('mousedown', function(e){
		if (isDown === false) {
			isDown = true;
			var pos = getMousePos(canvas, e);
			x1 = Math.round(pos.x);
			y1 = Math.round(pos.y);
		}
  });

	$hj(window).on('mouseup', function(e){
		if (isDown === true) {
			isDown = false;
      console.log('is Click ?');
//      console.log('x : '+x1+' - y : '+y1);
		}
	});

  $hj('#mapInterface > .draggable').draggable();
  $hj('#tokenInterface .draggable' ).draggable({ revert: true });
  
  $hj('.droppable').droppable({
  	drop: function( event, ui ) {
      var node = $hj(this);
      // Si on lache dans Trash (et qu'on vient de Canvas : TODO), faut supprimer
      if ( node.hasClass('trash') ) {
        var id = ui.draggable.attr('id');
        canvasAction('remove', id, '', '');
      }
      // Si on lache dans Canvas
      if ( node.attr('id')=='mapInterfaceBackground' ) {
        // Si on vient de TokenInterface, on créé une copie.
        var id = ui.draggable;
        if ( id.hasClass('copyCreate') ) {
	        var pos = getMousePos(canvas, event);
	        canvasAction('create', id.data('ref'), pos.x, pos.y);
        } else {
          var ratio = 1;
          if ( $hj('#mapInterface').hasClass('sm') ) { ratio = 2; }
          else if ( $hj('#mapInterface').hasClass('xs') ) { ratio = 4; }
          
          var left = $hj('#'+id.attr('id'))[0].offsetLeft*ratio;
          var top = $hj('#'+id.attr('id'))[0].offsetTop*ratio;
	        canvasAction('update', id.attr('id'), left, top);
        }
      }      
    }
  });
  
}

function setWorkZoneHeight() {
	// On veut fixer la hauteur de la #workZone pour qu'elle prenne toute la place non prise par les autres Ã©lÃ©ments.
  var h1 = ($hj('#actionsMission').length==0 ? 0 : $hj('#actionsMission')[0].offsetHeight);
	var h4 = $hj(window)[0].innerHeight;
  var wallMission = h4 - 32*$hj('#wpadminbar').length;
	$hj('#wallMission').height(wallMission);
  
	var nbArticles = $hj('#survivorsMission article').length;
  var survivorsHeight = wallMission-h1-2*(nbArticles-1);
  var articleHeight = survivorsHeight/Math.max(1, nbArticles);
  if ( articleHeight>140 ) { articleHeight = 140; }

  $hj('#survivorsMission article').each(function() {
    $hj(this).height(articleHeight);
    $hj(this).find('img').height(articleHeight);
  });
  $hj('#survivorsMission').height(survivorsHeight);
  $hj('#survivorsMission').width(articleHeight+58);
  
  $hj('#canvasMission').height(survivorsHeight);
  $hj('#canvasMission').css('left', articleHeight+60);

  $hj('#layouts').height(survivorsHeight);
  $hj('#layouts').css('left', articleHeight+60);
  
  // Une dalle peut avoir trois dimensions seulement. 125px, 250px, 500px
  // Selon la hauteur de la fenêtre et le nombre de dalles de la map en hauteur, on détermine cette dimension.
  // On doit vérifier que ça passe pour la largeur. Sans quoi, on doit réduire. Si on ne peut pas réduire, on ne le fait pas et on aura un scroll horizontal.
  var canvasMissionHeight = survivorsHeight;
  var canvasMissionWidth = $hj('#canvasMission').width();
  var mapInterfaceBackground = $hj('#mapInterfaceBackground');
  var nbRows = mapInterfaceBackground.data('rows');
  var nbCols = mapInterfaceBackground.data('cols');
	var tileDim = 500;
  while ( nbRows*tileDim > canvasMissionHeight && tileDim != 125 ) { tileDim /= 2; }
  while ( nbCols*tileDim > canvasMissionWidth && tileDim != 125 ) { tileDim /= 2; }
  drawCanvas(mapInterfaceBackground, tileDim);
  var ratio = 1;
  if ( $hj('#mapInterface').hasClass('sm') ) { ratio = 2; }
  else if ( $hj('#mapInterface').hasClass('xs') ) { ratio = 4; }
  $hj('#mapInterface').removeClass();
  if ( tileDim == 250 ) { $hj('#mapInterface').addClass('sm'); ratio /= 2; }
  else if ( tileDim == 125 ) { $hj('#mapInterface').addClass('xs'); ratio /= 4; }
  $hj('#mapInterface > div.token').each(function() { $hj(this).css({top: $hj(this).position().top*ratio, left: $hj(this).position().left*ratio}); });
}

function canvasAction(type, id, x, y) {
  var data = {'action': 'dealWithAjax', 'ajaxAction': 'canvasAction', 'type': type, 'id': id, 'x': x, 'y': y};
  $hj.post(
    ajaxurl,
    data,
    function(response) {
			console.log(response);
      switch ( type ) {
        case 'remove' :
          $hj('#'+id).remove();
        break;
        case 'create' :
          var newNode = $hj('<div>', {id: response, class: 'draggable token '+id, style: 'top: '+y+'px; left: '+x+'px;'});
          newNode.draggable();
          $hj('#mapInterfaceBackground').after(newNode);
        break;
      }
    }
  );
}

var tileDimRef = 0;
function drawCanvas(mapInterfaceBackground, tileDim) {
  // Si on n'a pas besoin de redimensionner, on ne le fait pas.
  if ( tileDimRef == tileDim ) { return; }
  tileDimRef = tileDim;
	var canvas = $hj('#mapInterfaceBackground')[0];
  if (!canvas) { console.log("Impossible de récupérer le canvas"); return; }
  var context = canvas.getContext('2d');
	if (!context) { console.log("Impossible de récupérer le context du canvas"); return; }
  
  var nbRows = mapInterfaceBackground.data('rows');
  var nbCols = mapInterfaceBackground.data('cols');
	canvas.width = nbCols*tileDim;
	canvas.height = nbRows*tileDim;
  
	var canvasDrawing = $hj('#mapInterfaceDrawing')[0];
  if (canvasDrawing) {
    canvasDrawing.width = nbCols*tileDim;
    canvasDrawing.height = nbRows*tileDim;
  }
  
  var mapCode = mapInterfaceBackground.data('code');
	var imageBg = new Image();
	imageBg.src = '/wp-content/plugins/zomb/web/rsc/img/map/'+mapCode+'_bg.jpg';
	imageBg.onload = function() {
		context.drawImage(imageBg, 0, 0, nbCols*500, nbRows*500, 0, 0, nbCols*tileDim, nbRows*tileDim);
	};
}

function getMousePos(canvas, evt) {
  var rect = canvas.getBoundingClientRect();
  return {
    x: evt.clientX - rect.left,
    y: evt.clientY - rect.top
  };
}

function fillIdWithContent(anchor, content) {
	if ( $hj('#'+anchor).length==1 ) {
		$hj('#'+anchor).html(content);
  }
}

function reloadIdElement(obj) {
	for ( var prop in obj ) {
		fillIdWithContent(prop, obj[prop]);
	}	
}

function setButtonAction() {
  /*** Boutons ShowWhole ***/
  //s_xps_and_inventory_
  $hj('button.showWhole').unbind().click(function() {
    var slid = $hj(this).find('i').data('slid');
    if ( !$hj('.layoutRightMission').is(':visible') ) {
      $hj('#s_xps_and_inventory_'+slid).addClass('layoutRightMission');
      $hj('#layouts').toggleClass('showContent');
    } else if ( !$hj('.layoutLeftMission').is(':visible') ) {
      $hj('#s_xps_and_inventory_'+slid).addClass('layoutLeftMission');
    } else {
      $hj('.layoutLeftMission').removeClass('layoutLeftMission');
      $hj('#s_xps_and_inventory_'+slid).addClass('layoutLeftMission');
    }
  });
  $hj('i.closeLayout').unbind().click(function() {
    if ( $hj(this).parent().hasClass('layoutRightMission') && $hj('.layoutLeftMission').is(':visible') ) {
	    $hj(this).parent().removeClass('layoutRightMission');
      $hj('.layoutLeftMission').removeClass('layoutLeftMission').addClass('layoutRightMission');
    } else {
	    $hj(this).parent().removeClass('layoutLeftMission').removeClass('layoutRightMission');
    }
    if ( !$hj('.layoutLeftMission').is(':visible') && !$hj('.layoutRightMission').is(':visible') ) {
      $hj('#layouts').toggleClass('showContent');
    }
  });
  
  $hj('#showTokenInterface').unbind().click(function() {
    $hj('.onlyOne').removeClass('active');
    $hj('#tokenInterface').addClass('active');
  });
  $hj('#showEquipmentInterface').unbind().click(function() {
    $hj('.onlyOne').removeClass('active');
    $hj('#equipmentInterface').addClass('active');
  });
  $hj('#showInvasionInterface').unbind().click(function() {
    $hj('.onlyOne').removeClass('active');
    $hj('#invasionInterface').addClass('active');
  });
  $hj('#showRollInterface').unbind().click(function() {
    $hj('.onlyOne').removeClass('active');
    $hj('#rollInterface').addClass('active');
  });

  $hj('#layoutLeftMission .skills i').unbind().click(function() {
    var data = {'action': 'dealWithAjax', 'ajaxAction': 'checkSkill', 'slsid': $hj(this).data('slsid')};
    $hj.post(ajaxurl, data, function(response) {} );
		$hj(this).toggleClass('glyphicon-check').toggleClass('glyphicon-unchecked');
  });
  buttonGrantLife();
  buttonGrantXp();
  buttonTrashCard();
  buttonDrawCard();
  buttonShowInvasion();

  $hj('#rollInterface button').unbind().click(function() {
    $hj.post(
      ajaxurl,
      {'action': 'dealWithAjax', 'ajaxAction': 'rollDice', 'diceCode': $hj('#rollInterface input').val()},
      function(response) {
				try {
					var obj = JSON.parse(response);
		    	reloadIdElement(obj);
				} catch (e) {
	      	console.log("error: "+e);
	      	console.log(response);
	    	}
      }
    );
  });
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function buttonShowInvasion() {
	$hj('.invasions .glyphicon-eye-open').hover(
    function() {
      $hj('#showInvasion').addClass('display'+capitalizeFirstLetter($hj(this).data('type')));
      $hj('#showInvasion div.blue').html($hj(this).data('blue'));
      $hj('#showInvasion div.yellow').html($hj(this).data('yellow'));
      $hj('#showInvasion div.orange').html($hj(this).data('orange'));
      $hj('#showInvasion div.red').html($hj(this).data('red'));
    },
    function() {
      $hj('#showInvasion').removeClass();
      $hj('#showInvasion div.blue').html('');
      $hj('#showInvasion div.yellow').html('');
      $hj('#showInvasion div.orange').html('');
      $hj('#showInvasion div.red').html('');
    },
  );
}

function buttonDrawCard() {
  $hj('#equipmentInterface .liDraw, #invasionInterface .liDraw').unbind().click(function() {
    var data = {};
		var obj;
    var type = $hj(this).data('type');
    var ajaxAction = ( type=='equipment' ? 'drawEquipment' : 'drawInvasion' );
    if ( $hj(this).data('nbdraw')!=undefined ) {
	    data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'draw': $hj(this).data('nbdraw')};
    }
    $hj.post(
      ajaxurl,
      data,
      function(response) {
				try {
					obj = JSON.parse(response);
		    	reloadIdElement(obj);
          buttonTrashCard();
          buttonShowInvasion();
				} catch (e) {
	      	console.log("error: "+e);
	      	console.log(response);
	    	}
      }
    );
  });
}

function buttonTrashCard() {
  $hj('.equipments i.glyphicon-trash, .invasions i.glyphicon-trash').unbind().click(function() {
    var type = $hj(this).parent().data('type');
    var data = {};
    if ( type=='equipment' ) {
      data = {'action': 'dealWithAjax', 'ajaxAction': 'trashEquipment', 'elid': $hj(this).parent().data('elid')};
    } else {
      data = {'action': 'dealWithAjax', 'ajaxAction': 'trashInvasion', 'ilid': $hj(this).parent().data('ilid')};
    }
    $hj.post(ajaxurl, data, function(response) {
      try {
        obj = JSON.parse(response);
        reloadIdElement(obj);
        buttonTrashCard();
        buttonShowInvasion();
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
    });
  });
}

function buttonGrantLife() {
  $hj('#survivorsMission .life li').unbind().click(function() {
    var data = {'action': 'dealWithAjax', 'ajaxAction': 'grantLife', 'slid': $hj(this).data('slid'), 'varpv': $hj(this).data('varpv')};
    $hj.post(ajaxurl, data, function(response) {
      try {
        obj = JSON.parse(response);
        reloadIdElement(obj);
        buttonGrantLife();
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
    });
  });
}

function buttonGrantXp() {
  $hj('#survivorsMission .xp li').unbind().click(function() {
    var data = {'action': 'dealWithAjax', 'ajaxAction': 'grantXp', 'slid': $hj(this).data('slid'), 'varxp': $hj(this).data('varxp')};
    $hj.post(ajaxurl, data, function(response) {
      try {
        obj = JSON.parse(response);
        reloadIdElement(obj);
        buttonGrantXp();
        var nb = $hj('#survivorsMission .xp li').length;
        if ( nb <= 7 ) { $hj('#survivorsMission .xp').removeClass().addClass('xp bgBlue'); }
        else if ( nb <= 19 ) { $hj('#survivorsMission .xp').removeClass().addClass('xp bgYellow'); }
        else if ( nb <= 43 ) { $hj('#survivorsMission .xp').removeClass().addClass('xp bgOrange'); }
        else { $hj('#survivorsMission .xp').removeClass().addClass('xp bgRed'); }
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
    });
  });
}