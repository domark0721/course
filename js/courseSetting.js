$(document).ready(function(){

  tinymce.init({
    selector: ".sectionEditor",
    theme: "modern",
    width: '100%',
    height: 300,
    language : 'zh_TW',
    statusbar : false,
    content_css: "css/tinyMceCustom.css",
    plugins: [
         "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code preview media fullpage | forecolor backcolor emoticons", 
     // style_formats: [
     //      {title: 'Bold text', inline: 'b'},
     //      {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
     //      {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
     //      {title: 'Example 1', inline: 'span', classes: 'example1'},
     //      {title: 'Example 2', inline: 'span', classes: 'example2'},
     //      {title: 'Table styles'},
     //      {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
     //  ]
   });

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


  $('#submitFormBtn').on('click', submitForm);

  function submitForm() {
      tinyMCE.triggerSave();
      
      // do some form validation


      $('#editSettingForm').submit();
  }


});