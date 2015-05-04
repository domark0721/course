$(document).ready(function(){

  tinyMCE.init({
    selector: ".sectionEditor",
    theme: "modern",
    width: '100%',
    height: 500,
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


  $('.chapterItem').on('click', function(e){
    console.log('hey');
      $('.sectionList').hide();

      $(this).next('.sectionList').fadeIn(300);
  });

  $('.sectionItem').on('click', function(e){
    e.preventDefault();
    showEditBlock($(e.target));
  });

  function showEditBlock(target){
    $('.sectionItem').removeClass('active');
    target.addClass('active');

    var activeSectionEditBlock = target.attr('href');

    $('.sectionEditWrap').hide();
    $(activeSectionEditBlock).fadeIn();
  }

  $('#saveCouseBtn').on('click', saveCourseContent);

  function saveCourseContent() {
    tinyMCE.triggerSave();

    // get all the data by fetching left nav bar
    // loop through each chapter to get formatted chapter objects
    var chapterList = $('.chapterList > li').map(function(){
        // first: for each chpater list, do below
        var chapter = $(this);
        // get chapter name
        var chapterName = chapter.find('.chapterName').html();
        
        // loop through each sectionItem under this chapter and get their formatted sectionObj
        var sectionList = chapter.find('.sectionItem').map(function(){
            // for each section item under this chapter, do below

            // get each sectionItem's href as corresponding right side editWrapDiv
            sectionWrapId = $(this).attr('href');
            // get name, video, content value of the section's editWrapDiv
            var sectionName = $(sectionWrapId).find('.section-name').val();
            var sectionVideo = $(sectionWrapId).find('.section-video').val();
            var sectionContent = $(sectionWrapId).find('.sectionEditor').val();
            
            // generate the formatted seciton obj of this secitonItem
            var sectionObj = {
              name : sectionName,
              video : sectionVideo,
              content : sectionContent 
            }

            return sectionObj;
        });

        // after getting chapter name and formatted section objs, generate chapter obj
        var chapterObj = {
          name : chapterName,
          secitons : sectionList.toArray()
        };

        return chapterObj;
    });

    chapters = chapterList.toArray();

    // ajax call save api (pass chapters and course_id as POST data)
    var request = $.ajax({
      url: "api/save_editCourse.php",
      type: "POST",
      data: { course_id : "" , chapters : chapters },
      dataType: "json"
    });
     
     // fix me :
    request.done(function( jData ) {
      if(jData.status=='ok'){
        alert('內容已儲存！')
        $("#saveStatus").html("最後編輯時間:"+jData.lastEditDate);
        $("#saveBtn").prop('disabled', false);
        $("#exitBtn").prop('disabled', false);
      }
      else{
        alert('內容儲存失敗！')
        $("#saveStatus").html("儲存錯誤，請重試");
        $("#saveBtn").prop('disabled', false);
        $("#exitBtn").prop('disabled', false);    
      }
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  }



});