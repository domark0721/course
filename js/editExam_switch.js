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

   // if has hash, default show the hash tab. if not, show first tab
  var defaultTabHash = window.location.hash;
  window.location.hash = '';

  if (defaultTabHash) {
      showEditor($('.tab-list a[href="' + defaultTabHash + '"]'));
  } else {
      showEditor($('.tab-list a').first());    
  } 
  
  // add question
  $('.add_tab-list a').on('click', function(e){
    e.preventDefault();
    show_newQuestionEditor($(e.target));
  });

  function show_newQuestionEditor(target) {
    $('.add_tab-list a').removeClass('active');
    target.addClass('active');
    var activeTab = target.attr('href');
    $('.add_tab-content').hide();
    $(activeTab).fadeIn();
  }

  show_newQuestionEditor($('.add_tab-list a').first());    

  // console.log($(this).is(":checked"));

});