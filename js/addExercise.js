$(document).ready(function(){
  
  $('.tab-list a').on('click', function(e){
    e.preventDefault();
    showEditor($(e.target));
  });

  function showEditor(target) {
    $('.tab-list a').removeClass('active');
    target.addClass('active');

    var activeTab = target.attr('href');
    $('.tab-content').hide();
    $(activeTab).fadeIn();
  }

  showEditor($('.tab-list a').first());

  $('#is_test_false, #is_test_false_single, #is_test_false_multi').on('click', function(e){
    // $('.showSection').fadeOut();
    $('#section_'+$(this).attr('target')).fadeOut();
  });

  $('#is_test_true, #is_test_true_single, #is_test_true_multi').on('click', function(e){
      $('#section_'+$(this).attr('target')).fadeIn();
  });

});