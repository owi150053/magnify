var closeMenu = function() {
    $('#side-bar').css({"width": "0%"});
    $('#overlay').fadeOut();
}

var moveMainOpen = function() {
    $('#main-content').css({"-ms-transform": "translate(-300px)",
    "-webkit-transform": "translate(-300px)",
    "transform": "translate(-300px)"});
}
var moveMainClose = function() {
    $('#main-content').css({"-ms-transform": "translate(0px)",
    "-webkit-transform": "translate(0px)",
    "transform": "translate(0px)"});
}

$(function(){
    $('#menu-btn').click(function(){
        $('#side-bar').css({"width": "300px"});
        $('#overlay').show();
        moveMainOpen();
    });
    
    $('#overlay').click(function(){
        $('.sign-up-modal').fadeOut(200);
        closeMenu();
        moveMainClose();
    })
    
    $('.close-menu').click(function(){
        closeMenu();
        moveMainClose();
    });
    
    $('.close-menu').mouseenter(function(){
        $(this).css({"transform": "rotate(90deg)"});
    }).mouseleave(function(){
        $(this).css({"transform": "rotate(0deg)"});
    });
    
    $('.close-sign-up').mouseenter(function(){
        $(this).css({"transform": "rotate(90deg)"});
    }).mouseleave(function(){
        $(this).css({"transform": "rotate(0deg)"});
    });
    
    $('#login-btn').click(function(){
        $('.sign-up-modal').css({"display": "block"});
        $('#overlay').show();
    });
    
    $('.close-sign-up').click(function(){
        $('.sign-up-modal').fadeOut(200);
        $('#overlay').fadeOut();
    });
    
    
    
});

$(document).keyup(function(e){
        if (e.keyCode == 27) {
            closeMenu();
            moveMainClose();
            $('.sign-up-modal').fadeOut(200);
        }
});