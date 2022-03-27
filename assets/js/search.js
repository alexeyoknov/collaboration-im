let searchRequest = null;
$('#search').keyup(function () {
  let minlength = 1;
  let value = $(this).val();
  let searchRow = $('#searchList').html('');
  if (value.length >= minlength) {
    if (searchRequest != null) searchRequest.abort();
    searchRequest = $.ajax({
      type: 'GET', url: 'search', data: {
        'search': value
      }, dataType: "text", success: function (msg) {
        let result = JSON.parse(msg);
        $.each(result, function (key, arr) {
          $.each(arr, function (id, name) {
            if (key === 'products') {
              if (id !== 'error') {
                searchRow.append('<li class="search-link" ><a href="/product-' + id + '">' + name + '</a></li>');
              } else {
                searchRow.append('<li>' + name + '</li>');
              }
            }
          });
        });
      }
    });
  }
});