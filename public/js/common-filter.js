(function(){
  function commonFiltersUpdateUrl(param,newValue)
  {
    var url = new URL($(location).attr('href'));
    if (url.searchParams.has(param))
      url.searchParams.set(param, newValue);
    else
      url.searchParams.append(param, newValue);

    return url;
  }

  async function commonFiltersQuery(url,data,response)
  {
    $.ajax({
      method: 'POST',
      url: url,
      data: data
      }).done(function (data) {
        $(response).html(data);
      });
  }

  $('.sort #input-amount').on('change',function(){
    document.location.href =  commonFiltersUpdateUrl('itemsPerPage',this.value);
  });

  $('ul.page-numbers li a').on('click',function(e){
    //document.location.href =  updateUrl('currentPage',this.id); 
    e.preventDefault();
    var a=$('ul.page-numbers li .current');
    var curId = $(location).attr('pathname').split('-');

    if (curId.length === 1)
      curId = 'all';
    else
      curId = curId[1];
    
    //a.removeClass('current');

    var b=$(this);
    //b.addClass('current');
    commonFiltersQuery("/all-products",{
      'categoryId': curId, 
      'view': 'default/layouts/parts/categories/categories-grid-list.html.twig',
      'limit': $('.sort #input-amount').val(),
      'offset': (this.id - 1)
    },'.shop-wraper .shop-total-product-area .tab-content').then(
      commonFiltersQuery("/category-pagination",{
        'limit': $('.sort #input-amount').val(),
        'currentPage': this.id,
        'itemsCount': $('#totalItemsCount').val()
      },'#pagination')
    );
    
    console.log($('#totalItemsCount').val());
    
  });
})();