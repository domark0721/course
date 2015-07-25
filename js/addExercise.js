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

  // 點擊章節需要
  $('#is_test_false, #is_test_false_short, #is_test_false_single, #is_test_false_multi').on('click', function(e){
    $('#section_'+$(this).attr('target')).fadeOut("slow", function() {
      $('#section_'+$(this).attr('target')).removeClass('show').fadeOut();
    });
  });

  $('#is_test_true, #is_test_true_short,  #is_test_true_single, #is_test_true_multi').on('click', function(e){
    $('#section_'+$(this).attr('target')).fadeIn("slow", function() {
        $(this).addClass("loader");
    });
  });

  $('#true_false').submit(function(){
      var question = $(this).find('.question_textarea').val();
      var answer = $(this).find('input:radio[name=answer]:checked').val();
      var level = $(this).find('input:radio[name=level]:checked').val();
      var min = $(this).find('.min').val();
      var sec = $(this).find('.sec').val();

      var formCheck = $(this).find('.formCheck');
      formCheck.html('');
      if(question.length == 0){
        formCheck.html('題目未填');
        return false;
      }else if(typeof answer === 'undefined'){
        formCheck.html('答案未選擇');
        return false;
      }else if(typeof level === 'undefined'){
        formCheck.html('難度未選擇');
        return false;
      }else if(min == 0 && sec == 0){
        formCheck.html('答題時間未選擇');
        return false;
      }
  });

  $('#short_answer').submit(function(){
      var question = $(this).find('.question_textarea').val();
      var answer = $(this).find('.short_answer').val();
      var level = $(this).find('input:radio[name=level]:checked').val();
      var min = $(this).find('.min').val();
      var sec = $(this).find('.sec').val();

      var formCheck = $(this).find('.formCheck');
      if(question.length == 0){
        formCheck.html('題目未填');
        return false;
      }else if(answer.length == 0){
        formCheck.html('答案未填');
        return false;
      }else if(typeof level === 'undefined'){
        formCheck.html('難度未選擇');
        return false;
      }else if(min == 0 && sec == 0){
        formCheck.html('答題時間未選擇');
        return false;
      }
  });

  $('#single_choice').submit(function(){
      var question = $(this).find('.question_textarea').val();
      var single_opt_content_1 = $(this).find('textarea[name=single_opt_content_1]').val();
      var single_opt_content_2 = $(this).find('textarea[name=single_opt_content_2]').val();
      var single_opt_content_3 = $(this).find('textarea[name=single_opt_content_3]').val();
      var single_opt_content_4 = $(this).find('textarea[name=single_opt_content_4]').val();
      var answer = $(this).find('input:radio[name=answer]:checked').val();
      var level = $(this).find('input:radio[name=level]:checked').val();
      var min = $(this).find('.min').val();
      var sec = $(this).find('.sec').val();

      var formCheck = $(this).find('.formCheck');
      if(question.length == 0){
        formCheck.html('題目未填');
        return false;
      }else if(single_opt_content_1.length == 0 || single_opt_content_2.length == 0 || single_opt_content_3.length == 0 || single_opt_content_4.length == 0){
        var hintString = '選項';
        if(single_opt_content_1.length == 0){
          hintString += '(1)';
        }
        if(single_opt_content_2.length == 0){
          hintString += '(2)';
        }
        if(single_opt_content_3.length == 0){
          hintString += '(3)';
        }
        if(single_opt_content_4.length == 0){
          hintString += '(4)';
        }
        formCheck.html(hintString+'內容未填');
        return false;
      }else if(typeof answer === 'undefined'){
        formCheck.html('答案未選擇');
        return false;
      }else if(typeof level === 'undefined'){
        formCheck.html('難度未選擇');
        return false;
      }else if(min == 0 && sec == 0){
        formCheck.html('答題時間未選擇');
        return false;
      }
  });
  
  $('#multi_choice').submit(function(){
      var question = $(this).find('.question_textarea').val();
      var multi_opt_content_1 = $(this).find('textarea[name=multi_opt_content_1]').val();
      var multi_opt_content_2 = $(this).find('textarea[name=multi_opt_content_2]').val();
      var multi_opt_content_3 = $(this).find('textarea[name=multi_opt_content_3]').val();
      var multi_opt_content_4 = $(this).find('textarea[name=multi_opt_content_4]').val();
      var multi_opt_content_5 = $(this).find('textarea[name=multi_opt_content_5]').val();
      var answers = $(this).find('input:checkbox:checked[name="answer[]"]').map(function(){ 
               return $(this).val(); 
            }).get();
      var level = $(this).find('input:radio[name=level]:checked').val();
      var min = $(this).find('.min').val();
      var sec = $(this).find('.sec').val();
      // console.log(answers);

      var formCheck = $(this).find('.formCheck');
      // console.log(formCheck);
      if(question.length == 0){
        formCheck.html('題目未填');
        return false;
      }else if(multi_opt_content_1.length == 0 || multi_opt_content_2.length == 0 || multi_opt_content_3.length == 0 || multi_opt_content_4.length == 0 || multi_opt_content_5.length == 0){
        var hintString = '選項';
        if(multi_opt_content_1.length == 0){
          hintString += '(1)';
        }
        if(multi_opt_content_2.length == 0){
          hintString += '(2)';
        }
        if(multi_opt_content_3.length == 0){
          hintString += '(3)';
        }
        if(multi_opt_content_4.length == 0){
          hintString += '(4)';
        }
        if(multi_opt_content_5.length == 0){
          hintString += '(5)';
        }
        formCheck.html(hintString+'內容未填');
        return false;
      }else if(answers.length === 0){
        formCheck.html('答案未選擇');
        return false;
      }else if(typeof level === 'undefined'){
        formCheck.html('難度未選擇');
        return false;
      }else if(min == 0 && sec == 0){
        formCheck.html('答題時間未選擇');
        return false;
      }
      
  });
  // console.log($(this).is(":checked"));

  $('.addSubQestionBtn').on('click', function(e){
      var html = '<div class="series_subquestion"><label for="question">小題題目</label><textarea class="question_textarea" name="question"></textarea><label for="single_opt_content">選項內容</label><div class="opt_content"><div><span>1</span><textarea name="single_opt_content_1"></textarea></div><div><span>2</span><textarea name="single_opt_content_2"></textarea></div><div><span>3</span><textarea name="single_opt_content_3"></textarea></div><div><span>4</span><textarea name="single_opt_content_4"></textarea></div></div><label for="single_answer">本題解答</label><div class="opt">  <input id="single_opt1" value="1" type="radio" name="answer"><label for="single_opt1" name="single_answer">(1)</label><input id="single_opt2" value="2" type="radio" name="answer"><label for="single_opt2" name="single_answer">(2)</label><input id="single_opt3" value="3" type="radio" name="answer"><label for="single_opt3" name="single_answer">(3)</label><input id="single_opt4" value="4" type="radio" name="answer"><label for="single_opt4" name="single_answer">(4)</label></div></div>';
      $('.series_subquestion_wrap').append(html);
  })

});