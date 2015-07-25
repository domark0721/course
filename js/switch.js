$(document).ready(function(){

    // bind all the tab with click event
    $('.tab-list a').on('click', function(e){
        e.preventDefault();
        showTab($(e.target));
    });

    // show tab function, pass in tab target(jquery obj)
    function showTab(target) {
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
        showTab($('.tab-list a[href="' + defaultTabHash + '"]'));
    } else {
        showTab($('.tab-list a').first());    
    }

    $('.finishBtns').on('click', function(e) {
        $('.correct_ans').show();
    });
	

    // showUnderline(this);
	
	// function showUnderline(elem){
	// 	var id = $(elem).attr("id");
    //    	alert(id);
	// }
        // Get the tab list of names first
    // var tablist = $('.tab-list a').map(function(i, tab){
       //  return $(tab).attr('href');
    // });

})