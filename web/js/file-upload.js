$(function(){
    
    function tempIMG(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#file").change(function(){
        tempIMG(this);
    });
    
    
    
    
    
    
    $('#file').change(function(e){
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