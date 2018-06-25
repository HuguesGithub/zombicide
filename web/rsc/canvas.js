var $hj = jQuery;
$hj(document).ready(function(){
  /***************
   *** Canvas Background
   ***************/
	var canvas = $hj('#mapInterface')[0];
  /*
	if (!canvas) { console.log("Impossible de récupérer le canvas"); return; }
	var context = canvas.getContext('2d');
	if (!context) { console.log("Impossible de récupérer le context du canvas"); return; }
	var mapData = JSON.parse(mapJson);
	var map = mapData.map;
	canvas.width = map.width*500;
	canvas.height = map.height*500;
	var imageBg = new Image();
	imageBg.src = '/wp-content/plugins/zomb/web/rsc/img/map/'+map.code+'_bg.jpg';
	imageBg.onload = function() {
		context.drawImage(imageBg, 0, 0, map.width*500, map.height*500, 0, 0, map.width*500, map.height*500);
	};
  */
    
  /***************
   *** Canvas Tokens
   ***************/
	var canvasToken = $hj('#tokenInterface')[0];
  /*
	if (!canvasToken) { console.log("Impossible de récupérer le canvas"); return; }
	var contextToken = canvasToken.getContext('2d');
	if (!contextToken) { console.log("Impossible de récupérer le context du canvas"); return; }

	var tokens = mapData.tokens;
	loadAndDisplayTokens(tokens, contextToken, 0);
	var survivors = mapData.survivors;
	loadAndDisplaySurvivors(survivors, contextToken, 0);
  */
  
  
  
  
	var isDown = false;
	var x1, y1;

	$hj('#tokenInterface').on('mousedown', function(e){
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
      console.log('x : '+x1+' - y : '+y1);
      /*
			$hj('#coordXToken').val(x1);
			$hj('#coordYToken').val(y1);
      */
		}
	});
});

function loadAndDisplayTokens(tokens, contextToken, cpt) {
  if (cpt == tokens.length ) { return; }
  var token = tokens[cpt];
  var image = new Image();
  image.src = '/wp-content/plugins/zomb/web/rsc/img/tokens/'+token.code+'.png';
  image.onload = function() {
    contextToken.drawImage(image, token.x, token.y);
	  loadAndDisplayTokens(tokens, contextToken, cpt+1);
  }
}

function loadAndDisplaySurvivors(survivors, contextToken, cpt) {
	if (cpt == survivors.length ) { return; }
	var survivor = survivors[cpt];
	var image = new Image();
	if (survivor.type == 'survivor' ) {
		image.src = '/wp-content/plugins/zomb/web/rsc/img/portraits/'+survivor.code+'.jpg';
	} else if (survivor.type == 'zombie' ) {
		image.src = '/wp-content/plugins/zomb/web/rsc/img/tokens/'+survivor.code+'.png';
	}
	image.onload = function() {
		contextToken.drawImage(image, survivor.x, survivor.y, survivor.width, survivor.width);
		loadAndDisplaySurvivors(survivors, contextToken, cpt+1);
	}
}





function getMousePos(canvas, evt) {
  var rect = canvas.getBoundingClientRect();
  return {
    x: evt.clientX - rect.left,
    y: evt.clientY - rect.top
  };
}


/*
		var mapToken = $j('#canvasTokens')[0];
		var tokCtx= mapToken.getContext('2d');

	});
*/