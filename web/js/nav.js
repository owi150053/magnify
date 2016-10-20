var closeMenu = function() {
    $('#side-bar').css({"width": "0%"});
    $('#overlay').fadeOut();
}

var moveMainOpen = function() {
    $('#main-content').css({"-ms-transform": "translate(-300px)",
    "-webkit-transform": "translate(-300px)",
    "transform": "translate(-300px)"});
}

var moveSideContent = function() {
    $('#dash-side-bar').css({"left": "-300px"});
}

var closeSideContent = function() {
    $('#dash-side-bar').css({"left": "0px"});
}

var moveMainClose = function() {
    $('#main-content').css({"-ms-transform": "translate(0px)",
    "-webkit-transform": "translate(0px)",
    "transform": "translate(0px)"});
}

$(function(){
    
    $('#dash-side-bar').mouseenter(function(){
        $('.edit-p').fadeIn(200);
    });
    
    $('#dash-side-bar').mouseleave(function(){
        $('.edit-p').fadeOut(200);
    });
    
    $('.edit-p').click(function(){
        $('#overlay').show().css({"z-index": "3"});
        $('.info-edit').show();
        $('.avatar-edit').fadeOut(200);
    });
    
    $('.edit-pic').click(function(){
        $('#overlay').show().css({"z-index": "3"});
        $('#side-bar').css({"z-index":"2"});
        $('nav').css({"z-index":"2"});
        $('.avatar-edit').show();
        $('.info-edit').fadeOut(200);
    });
    
    $('#menu-btn').click(function(){
        $('#side-bar').css({"width": "300px"});
        $('#overlay').show();
        moveMainOpen();
        moveSideContent();
    });
    
    $('#overlay').click(function(){
        $('.sign-up-modal').fadeOut(200);
        closeMenu();
        moveMainClose();
        closeSideContent();
        $('.avatar-edit').fadeOut(200);
        $('#side-bar').css({"z-index":"9"});
        $('nav').css({"z-index":"5"});
        $('.info-edit').fadeOut(200);
    })
    
    $('.close-menu').click(function(){
        closeMenu();
        moveMainClose();
        closeSideContent();
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
            closeSideContent();
            $('.sign-up-modal').fadeOut(200);
            $('.avatar-edit').fadeOut(200);
            $('#side-bar').css({"z-index":"9"});
            $('nav').css({"z-index":"5"});
            $('.info-edit').fadeOut(200);
        }
});