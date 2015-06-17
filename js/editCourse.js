$(document).ready(function(){
  
  bindChapterItem();
  bindSectionItem();

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
  
  function bindSectionItem(){
    $('.sectionItem').on('click', function(e){
      e.preventDefault();
      // console.log('hey');
      showEditBlock($(e.target));
    });
  }

  function showEditBlock(target){
    $('.sectionItem').removeClass('active');
    target.addClass('active');

    var activeSectionEditBlock = target.attr('href');

    $('.sectionEditWrap').hide();
    $(activeSectionEditBlock).fadeIn();
  }

  /******** add new chapter Start ********/
  $('.addChapterBtn').on('click', function(e){
    $chapterList = $(this).next('.chapterList');
    // $chapterList = $btnNext.children('.chapter:last-child');
    // console.log($chapterList);
    var ChapterCount = $(".chapter").length;
    var emptyChapter = '<li class="chapter">';
    emptyChapter += '<div class="chapterItem">';
    emptyChapter += '<i class="fa fa-bookmark-o"></i>';
    emptyChapter += '<span class="chapterNo"> CH'+ (ChapterCount+1)+': </span>';
    emptyChapter += '<span class="chapterName">新章節</span>';
    emptyChapter += '<span class="chapter-btns">';
    emptyChapter += '<a class="addSectionBtn" data-chapter-id="'+ (ChapterCount+1) +'"><i class="fa fa-plus-circle"></i></a>';
    emptyChapter += '<a class="deleteChapterBtn"><i class="fa fa-trash-o"></i></a></span></div>';
    emptyChapter += '<ul class="sectionList"></ul></li>';
    // emptyChapter += '';

    $chapterList.append(emptyChapter);
    bindChapterItem();
  });
  /******** add new chapter End ********/
  
  /******** add new section Start ********/
  function bindChapterItem(){

    $('.chapterItem').on('click', function(e){
        $('.sectionList').hide();

        $(this).next('.sectionList').fadeIn(300);
    });

    $('.addSectionBtn').on('click', function(e){
        console.log('add section');
        $chapterParent = $(this).parents('.chapter');
        $sectionList = $chapterParent.find('.sectionList');
        // console.log($sectionList);
        var chapterNo = $(this).data('chapter-id');
        console.log(chapterNo);
        var editWrapCount = $(".sectionItem").length;
        var sectionCount = $sectionList.find('.sectionItem').length;
        var sectionNo = chapterNo + '-' + (sectionCount+1) + ': ';
        var section_uid = (Math.floor(Math.random()*90000) + 10000) + new Date().valueOf().toString();
        // add default section left part into section list
        var emptySection = '<li class="section">';
        emptySection += '<a class="sectionItem" href="#editWrap'+ editWrapCount+'">';
        emptySection += '<span class="sectionNo">'+ sectionNo +'</span>';
        emptySection += '新小節';
        emptySection += '<div class="chapter-btns"><span class="deleteSectionBtn"><i class="fa fa-trash-o"></i></span></div></a></li>';

        $sectionList.append(emptySection);

        // add default section right part into right panel

        var emptyEditor = '<div id="editWrap'+ editWrapCount +'" class="sectionEditWrap" >';
        emptyEditor += '<div class="sectionName"><label for="sectionName">章節名稱</label>';
        emptyEditor += '<input class="sectionNameInput section-name" name="sectionName" value="新小節"></div>';
        emptyEditor += '<div class="sectionVideo"><label for="sectionVideo">章節影片</label>';
        emptyEditor += '<input type="file" name="sectionVideo" class="section-video"><div id="video"><video controls>';
        emptyEditor += '<source src="" type="video/mp4">Your browser does not support the video tag.</video></div></div>';
        emptyEditor += '<div class="sectionEditorWrap"><label for="sectionContent">章節內容</label>';
        emptyEditor += '<textarea id="tinyMce_'+editWrapCount+'" class="sectionEditor section-content" style="width:100%"></textarea></div>';
        emptyEditor += '<input type="hidden" class="section-uid" value="'+ section_uid +'"></div>';
        $('#rightPanel').append(emptyEditor);
        tinyMCE.execCommand('mceAddEditor', false, 'tinyMce_'+editWrapCount);
        bindSectionItem();
      });
  }
  /******** add new section End ********/

  $('#saveCouseBtn').on('click', saveCourseContent);

  function saveCourseContent() {
    var course_id = $('#course_id').val();
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
            var sectionUid = $(sectionWrapId).find('.section-uid').val();
            var sectionVideo = $(sectionWrapId).find('.section-video').val();
            var sectionContent = $(sectionWrapId).find('.sectionEditor').val();
            
            // generate the formatted seciton obj of this secitonItem
            var sectionObj = {
              name : sectionName,
              uid : sectionUid,
              video : sectionVideo,
              content : sectionContent 
            }

            return sectionObj;
        });

        // after getting chapter name and formatted section objs, generate chapter obj
        var chapterObj = {
          name : chapterName,
          sections : sectionList.toArray()
        };

        return chapterObj;
    });

    chapters = chapterList.toArray();

    // ajax call save api (pass chapters and course_id as POST data)
    var request = $.ajax({
      url: "api/save_editCourse.php",
      type: "POST",
      data: { course_id : course_id , chapters : chapters },
      dataType: "json"
    });
     
     // fix me :
    request.done(function( jData ) {
      if(jData.status=='ok'){
        alert('內容已儲存！');
        console.log(jData);
        $("#saveStatus").html("最後儲存時間<br>"+jData.lastEditDate);
        $("#saveBtn").prop('disabled', false);
        $("#exitBtn").prop('disabled', false);
      }
      else{
        alert('內容儲存失敗！');
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