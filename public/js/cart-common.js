(function(){
  $('td.remove-item a').on('click', function(e){
    e.preventDefault();
    let v=$(this).attr('id').split('-');
    let action = $('form #formAction');
    if (action) {
      action.attr('name','cart[orderProducts][' + v[1] + '][remove]');
      $("form[name='cart']").submit();
    }
  });

  $('form #cart_save').on('click', function(e){
    e.preventDefault();
    let action = $('form #formAction');
    if (action) {
      action.attr('name','cart[save]');
      $("form[name='cart']").submit();
    }
  });

  $('form #cart_clear').on('click', function(e){
    e.preventDefault();
    let action = $('form #formAction');
    if (action) {
      action.attr('name','cart[clear]');
      $("form[name='cart']").submit();
    }
  });
})();