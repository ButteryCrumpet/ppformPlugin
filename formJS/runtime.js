(function($) {
$('document').ready(function(){
  //jpostal
  $('#yuzip').jpostal({
    postcode : [
      '#yuzip'
    ],
    address : {
      '#ken': '%3',
      '#address' : '%4%5',
    }
  });

  $('.ppform').autoValid();
  
});
})(jQuery);