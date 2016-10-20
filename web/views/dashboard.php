<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <link rel="stylesheet" href="/magnify/web/css/font-awesome.css">
    <link rel="stylesheet" href="/magnify/web/css/main.css">
    <link rel="stylesheet" href="/magnify/web/css/dash.css">
    <link rel="stylesheet" href="/magnify/web/css/upload.css">
    <link href="https://fonts.googleapis.com/css?family=Ledger" rel="stylesheet">
    
    <script src="/magnify/web/js/nav.js" type="text/javascript"></script>
    <script src="/magnify/web/js/load.js" type="text/javascript"></script>
    <script src="/magnify/web/js/file-upload.js" type="text/javascript"></script>
</head>
<body>
    <div id="preload">
        <span class="fa fa-circle-o-notch fa-spin fa-3x white"></span>
        <b class="loading white">LOADING</b>
    </div>
    <header>
        <nav>
            <li id="logout-btn" class="dash-right"><a href="/magnify/web/logout"><span class="fa fa-sign-out" aria-hidden="true"></span>&nbsp;SIGN OUT</a></li>
            <li id="menu-btn" class="dash-right"><span class="fa fa-bars right menu-icon white" aria-hidden="true"></span></li>
        </nav>
        <div id="dash-side-bar">
            <a href="/magnify/web"><img class="dash-logo" src="/magnify/web/images/logos/LogoWhite-01.png"></a>
            <span class="fa fa-pencil fa-lg white edit-p"></span>
            <div id="update-avatar">
                <div class="avatar" style="background:url(/magnify/web{{ avatar }}) center;background-size:cover;">
                    <div class="edit-pic"><span class='fa fa-pencil white' aria-hidden='true'></span></div>
                </div>
            </div>
            <!--PROFILE EDIT-->
            <div class="avatar-edit">
                <p id="error"></p>
                <form name="avatar-edit" action="/magnify/web/settings/avatar" enctype="multipart/form-data">
                    <input name="avatar-file" class="inputfile" id="avatar-file" type="file">
                    <label class="files-upload img-upload" for="avatar-file"><p>SELECT PICTURE</p></label>
                    <input type="submit" id="avatar-save" name="avatar-save" value="SAVE">
                </form>
                <span class="triangle"></span>
            </div>
            <div class="info-edit">
                <form name="info-edit" action="/magnify/web/settings/info">
                    <p class="error"></p>
                    <label for="name-change" class="edit-label">EDIT NAME:</label>
                    <input type="text" id="name-change" class="profile-info-input" value="{{ name | default('') }}">
                    <p class="error"></p>
                    <label for="surname-change" class="edit-label">EDIT SURNAME:</label>
                    <input type="text" id="surname-change" class="profile-info-input" value="{{ surname | default('') }}">
                    <p class="error"></p>
                    <label for="email-change" class="edit-label">EDIT EMAIL:</label>
                    <input type="text" id="email-change" class="profile-info-input" value="{{ email | default('') }}">
                    <input type="submit" id="update-profile" value="UPDATE">
                </form>
                <span class="triangle"></span>
            </div>
            <!--USER NAME-->
            <ul class="center white">
                <li><a id="username" href="/magnify/web/dashboard">{{ name | default('') }} {{ surname | default('') }}</a></li>
            </ul><a href="/magnify/web/upload" class="create-post-btn"><p><span class='fa fa-pencil-square-o fa-lg fa-pull-left' aria-hidden='true'></span>CREATE</p></a>
        </div>
        <div id="side-bar">
            <span class="fa fa-times close-menu" aria-hidden="true"></span>
            <ul>
                <li><a href="/magnify/web/">HOME</a></li>
                <li>LATEST POSTS</li>
                <li>CONTACT US</li>
            </ul>
            <div class="login-logout white">
                {% if name is not null %}
                <a href="/magnify/web/dashboard" class="profile-menu">
                    <p><span class='fa fa-user fa-lg' aria-hidden='true'></span>&nbsp;&nbsp;&nbsp;PROFILE</p>
                </a>
                <a href="/magnify/web/logout" class="logout-menu">
                    <p><span class='fa fa-sign-out fa-lg' aria-hidden='true'></span>&nbsp;SIGN OUT</p>
                </a>
                {% elseif name is null %}
                <a href="/magnify/web/login-page" class="profile-menu black">
                    <p><span class='fa fa-user fa-lg' aria-hidden='true'></span>&nbsp;&nbsp;&nbsp;SIGN UP</p>
                </a>
                <a href="/magnify/web/login-page" class="logout-menu">
                    <p><span class='fa fa-sign-out fa-lg' aria-hidden='true'></span>&nbsp;LOG IN</p>
                </a>
                {% endif %}
            </div>
        </div>
        
        <div id="overlay"></div>
    </header>
    {% block content %}
    
    {% endblock %}
    
    
    <script type="text/javascript">
        $("#avatar-save").click(function(event){
            event.preventDefault();
            var input = $('#avatar-file').val().split('\\').pop();
            console.log($('input[name="avatar-file"]')[0].files[0]);
            console.log('/images/' + input);
            $.ajax({
                url: '/magnify/web/settings/avatar',
                method: 'POST',
                data: {avatarFile: input, fileUrl: $('input[name="avatar-file"]')[0].files[0]},
                contentType: "application/x-www-form-urlencoded",
                processData: false,
                enctype: "multipart/form-data"
            }).done(function(html) {
               $("#update-avatar").html(html);
                console.log(input);
            }).fail(function() {
                $('#error').html(
                "The upload failed please try again.");
            });
        });
        
        
        $("#update-profile").click(function(event){
            event.preventDefault();
            $.ajax({
                url: '/magnify/web/settings/info',
                method: 'POST',
                data: {nameC: $('#name-change').val(), surnameC: $('#surname-change').val(), emailC: $('#email-change').val() },
            }).done(function(html) {
                $("#username").html($('#name-change').val() + " " + $('#surname-change').val());
                $('#overlay').hide().css({"z-index": "7"});
                $('.info-edit').hide();
            }).fail(function() {
                $('#error').html(
                "The upload failed please try again.");
            });
        });
    </script>
    
    
</body>
</html>