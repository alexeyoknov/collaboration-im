(function() {
   
  $('#headerCart').on('click','.header-cart div.remove a', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    
    //найти родителя у родителя a: a => div.remove => div.content и у него взять id
    let parent = $(this).parent().parent();
    let v=$(parent).attr('id').split('-');
    
    $.ajax({
      method: 'POST',
      url: '/cart/remove/' + v[1],
      data: {
        'responseTotal': true,
      }
    }).done(function (data) {
      //console.log(data);
      $('.header-cart .cart-subtotal span').text(data.total);
      let itemsCount = $('.header-cart .cart-icon span');
      $(itemsCount).text($(itemsCount).text()-1);
    });
    
    $('#headerCart .header-cart #cartHeader-'+v[1]).remove();
    return false;
  });
  
  function addProductToCart(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let id = $(this).attr('id');

    $.ajax({
      method: 'POST',
      url: "/cart-product-add/" + id,
      data: {}
    }).done(function (data) {
      //console.log(data);
      //console.log('added data for#' + id);
      $('#headerCart').html(data.response);
    });
    return false;
  }
  
  $('#grid .single-product a.add-cart').on("click", addProductToCart);
  $('#list .single-product a.add-cart').on("click", addProductToCart);
  $('#new-arrival .single-product a.add-cart').on("click", addProductToCart);
  $('#random-products .single-product a.add-cart').on("click", addProductToCart);
})();