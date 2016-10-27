$(function(){
    $('#reg-name').keyup(function(){
        if ($(this).val().length < 3) {
            $('#sign-up-btn').prop('disabled', true);
            $('#sign-up-btn').addClass('danger-btn');
            $('.errN').addClass('block error');
            $('.errN').removeClass('none');
        } else {
            $('#sign-up-btn').prop('disabled', false);
            $('#sign-up-btn').removeClass('danger-btn');
            $('.errN').removeClass('block error');
            $('.errN').addClass('none');
        }
    });
    
    $('#reg-surname').keyup(function(){
        if ($(this).val().length < 3) {
            $('#sign-up-btn').prop('disabled', true);
            $('#sign-up-btn').addClass('danger-btn');
            $('.errS').addClass('block error');
            $('.errS').removeClass('none');
        } else {
            $('#sign-up-btn').prop('disabled', false);
            $('#sign-up-btn').removeClass('danger-btn');
            $('.errS').removeClass('block error');
            $('.errS').addClass('none');
        }
    });
    
    $('#reg-email').keyup(function(){
        if ($(this).val().length < 3) {
            $('#sign-up-btn').prop('disabled', true);
            $('#sign-up-btn').addClass('danger-btn');
            $('.errE').addClass('block error');
            $('.errE').removeClass('none');
        } else {
            $('#sign-up-btn').prop('disabled', false);
            $('#sign-up-btn').removeClass('danger-btn');
            $('.errE').removeClass('block error');
            $('.errE').addClass('none');
        }
    });
    
    $('#reg-password').keydown(function(){
        if ($(this).val().length < 7) {
            $('#sign-up-btn').prop('disabled', true);
            $('#sign-up-btn').addClass('danger-btn');
            $('.errP').addClass('block error');
            $('.errP').removeClass('none');
        } else if($(this).val().length > 7) {
            $('#sign-up-btn').prop('disabled', false);
            $('#sign-up-btn').removeClass('danger-btn');
            $('.errP').removeClass('block error');
            $('.errP').addClass('none');
        }
    });
    
    $('#name-change').keyup(function(){
        if ($(this).val().length < 3) {
            $('#update-profile').prop('disabled', true).addClass('danger-btn');
            $('.errN').addClass('block error');
            $('.errN').removeClass('none');
        } else {
            $('#update-profile').prop('disabled', false)
            $('#update-profile').removeClass('danger-btn');
            $('.errN').removeClass('block error');
            $('.errN').addClass('none');
        }
    });
    
    $('#surname-change').keyup(function(){
        if ($(this).val().length < 3) {
            $('#update-profile').prop('disabled', true).addClass('danger-btn');
            $('.errS').addClass('block error');
            $('.errS').removeClass('none');
        } else {
            $('#update-profile').prop('disabled', false)
            $('#update-profile').removeClass('danger-btn');
            $('.errS').removeClass('block error');
            $('.errS').addClass('none');
        }
    });
    
    $('#email-change').keyup(function(){
        if ($(this).val().length < 3) {
            $('#update-profile').prop('disabled', true).addClass('danger-btn');
            $('.errE').addClass('block error');
            $('.errE').removeClass('none');
        } else {
            $('#update-profile').prop('disabled', false)
            $('#update-profile').removeClass('danger-btn');
            $('.errE').removeClass('block error');
            $('.errE').addClass('none');
        }
    });
    
});