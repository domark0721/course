console.log($('#footer').offset().top);
function checkOffset() {
    if($('#social-float').offset().top + $('#social-float').height() 
                                           >= $('#footer').offset().top - 100)
        $('#social-float').css('position', 'absolute');
    if($(document).scrollTop() + window.innerHeight < $('#footer').offset().top)
        $('#social-float').css('position', 'fixed'); // restore when you scroll up
   <!--  $('#social-float').text($(document).scrollTop() + window.innerHeight); -->
}
$(document).scroll(function() {
    checkOffset();
});

