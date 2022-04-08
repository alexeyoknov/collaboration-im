(function () {
  $("#a_register").on('click', function (e) {
    e.preventDefault();
    $('form[name="registration_form"]').submit();
  });
   
})();