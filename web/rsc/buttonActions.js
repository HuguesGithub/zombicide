var $hj = jQuery;
$hj(document).ready(function(){
  bindLiveActionButtons();
  
  $hj('#livewrap-overlay').click(function(){
    $hj('#livewrap-overlay').removeClass('active');
    $hj('#livewrap-popup').removeClass('active');
  });
  
});

function bindLiveActionButtons() {
  $hj('#liveactionwrap button').unbind().click(function() {
    var action = $hj(this).data('action');
    dealWithButton(action);
  });
}

function dealWithButton(action) {
  switch (action ) {
    case 'select-survivor' : dealWithStartButton(); break;
      default : console.log('Y a un problème avec la valeur du paramètre : ['+action+']');
  }
}

function dealWithStartButton() {
  var obj;
  var data = {'action': 'dealWithAjax', 'ajaxAction': 'getPopupSelectSurvivor', 'value': 'turn'};
  $hj.post(
    ajaxurl,
    data,
    function(response) {
      try {
        obj = JSON.parse(response);
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
      $hj('#livewrap-overlay').addClass('active');
      $hj('#livewrap-popup').addClass('active').html(obj.popupContent);
    }
  );
}