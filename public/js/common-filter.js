(function(){

  function commonFiltersUpdateUrl(param,newValue)
  {
    let url = new URL($(location).attr('href'));
    if (url.searchParams.has(param))
      url.searchParams.set(param, newValue);
    else
      url.searchParams.append(param, newValue);

    return url;
  }
  
  function getViewType()
  {
    let a=$("ul#viewType li.active a");
    return a ? a.attr('aria-controls') : 'grid';
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
    let url = commonFiltersUpdateUrl('currentPage',1);
    window.history.pushState({}, '', url);
    document.location.href =  commonFiltersUpdateUrl('itemsPerPage',this.value);
  });

  $('.sort #input-sort').on('change',function(){
    let [orderBy, orderType] = this.value.split('-');
    let url = commonFiltersUpdateUrl('orderBy',orderBy);
    window.history.pushState({}, '', url);
    document.location.href =  commonFiltersUpdateUrl('orderType',orderType);
  });
  
  $("ul#viewType li a").on('click', function(){
    let url = commonFiltersUpdateUrl('viewType', $(this).attr('aria-controls'));
    window.history.pushState({}, '', url);
  });
  
  $('ul.page-numbers li a').on('click',function(e){
    //document.location.href =  updateUrl('currentPage',this.id); 
    e.preventDefault();
    let a=$('ul.page-numbers li .current');
    let curId = $(location).attr('pathname').split('/');
    
    if (curId.length === 2)
      curId = 'all';
    else
      curId = curId[curId.length-1];
    
    commonFiltersQuery("/all-products",{
      'categoryId': curId, 
      'view': 'default/layouts/parts/categories/categories-grid-list.html.twig',
      'limit': $('.sort #input-amount').val(),
      'offset': (this.id - 1),
      'viewType':getViewType(),
    },'.shop-wraper .shop-total-product-area .tab-content').then(
      commonFiltersQuery("/category-pagination",{
        'limit': $('.sort #input-amount').val(),
        'currentPage': this.id,
        'itemsCount': $('#totalItemsCount').val()
      },'#pagination')
    );
    let showfrom = (this.id - 1) * $('.sort #input-amount').val() + 1;
    let showto = this.id * $('.sort #input-amount').val();
    let total = $('#totalItemsCount').val();
    if (showto>total)
      showto = total;
    $("span#showFrom").text(showfrom);
    $("span#showTo").text(showto);
    
    let url = commonFiltersUpdateUrl('currentPage', this.id);
    window.history.pushState({}, '', url);

  });
})();