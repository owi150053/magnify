$(function(){
    $('#avatar-file').change(function(e){
        var fileName = e.target.value.split( '\\' ).pop();
        console.log(fileName);
        $('.img-upload').children().html(fileName);
    });
    
    $('#header-image-file').change(function(e){
        var fileName = e.target.value.split( '\\' ).pop();
        console.log(fileName);
        $('.img-upload').children().html(fileName);
    });
});