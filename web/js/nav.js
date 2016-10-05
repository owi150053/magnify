var closeMenu = function() {
    $('#side-bar').css({"width": "0%"});
    $('#overlay').hide();
}

var moveMainOpen = function() {
    $('#main-content').css({"margin-right": "300px"});
}
var moveMainClose = function() {
    $('#main-content').css({"margin-right": "0%"});
}

$(function(){
    $('#menu-btn').click(function(){
        $('#side-bar').css({"width": "300px"});
        $('#overlay').show();
        moveMainOpen();
    });
    
    $('#overlay').click(function(){
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
    
    
    
});

$(document).keyup(function(e){
        if (e.keyCode == 27) {
            closeMenu();
            moveMainClose();
        }
});