<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="/magnify/web/css/font-awesome.css">
    <link rel="stylesheet" href="/magnify/web/css/main.css">
    <link rel="stylesheet" href="/magnify/web/css/home.css">
    <link rel="stylesheet" href="/magnify/web/css/contact-us.css">
    <link rel="stylesheet" href="/magnify/web/css/upload.css">
    <link href="https://fonts.googleapis.com/css?family=Ledger" rel="stylesheet">
    <link rel="stylesheet" href="/magnify/web/css/fonts.css">
    
    <script src="/magnify/web/js/nav.js" type="text/javascript"></script>
    <script src="/magnify/web/js/load.js" type="text/javascript"></script>
    <script src="/magnify/web/js/animation.js" type="text/javascript"></script>
    <script src="/magnify/web/js/validation.js" type="text/javascript"></script>
    <script src="/magnify/web/js/file-upload.js" type="text/javascript"></script>
</head>
<body>
    <div id="preload">
        <span class="fa fa-circle-o-notch fa-spin fa-3x white"></span>
        <b class="loading white">LOADING</b>
    </div>
    <header>
        <nav>
            {% if name is not null %}
            <li class="black inline upper hello">Hi, <a class="underline black" href="/magnify/web/dashboard">{{ name | default('') }}</a> | </li>
                <li id='logout-btn' class='dash-right inline black home-logout'><a href='/magnify/web/logout'><span class='fa fa-sign-out' aria-hidden='true'></span>&nbsp;SIGN OUT</a></li>            
            {% elseif name is null %}
                <li id='login-btn'><span class='fa fa-user' aria-hidden='true'></span>&nbsp;LOGIN | SIGN UP</li>
            {% endif %}
            
            <li id="menu-btn"><span class="fa fa-bars menu-icon" aria-hidden="true"></span></li>
        </nav>
        <div id="side-bar">
            <span class="fa fa-times close-menu" aria-hidden="true"></span>
            <ul>
                <a href="/magnify/web/"><li>HOME</li></a>
                <li>LATEST POSTS</li>
                <a href="/magnify/web/contact-us"><li>CONTACT US</li></a>
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
        <div class="sign-up-modal animateZoom {{ errors['display'] | default('') }} {{ errors['sign-display'] | default('') }}">
            <div id="signup-suc" class="section">
                
                <h3 class="white center">SIGN UP</h3>
                <form id="sign-up" action="/magnify/web/signup" method="post">
                    <span class="errN danger none center white">Name must be longer than 2 characters</span>
                    <input type="text" id="reg-name" name="reg-name" placeholder="ENTER YOUR NAME" autocomplete="off">
                    <span class="errS danger none center white">Surname must be longer than 2 characters</span>
                    <input type="text" id="reg-surname" name="reg-surname" placeholder="ENTER YOUR SURNAME" autocomplete="off">
                    <span class="danger {{ errors['sign-display'] | default('') }} center white">{{ errors['email-exists'] | default('') }}</span>
                    <span class="errE danger none center white">Surname must be longer than 2 characters</span>
                    <input type="text" id="reg-email" name="reg-email" placeholder="ENTER YOUR EMAIL" autocomplete="off">
                    <input type="password" id="reg-password" name="reg-password" placeholder="CHOOSE A PASSWORD" autocomplete="off">
                    <input type="submit" id="sign-up-btn" name="sign-up" value="SIGN UP">
                    <span id="sign-form-error" class="danger center error block">{{ errors['form'] | default('') }}</span>
                </form>
            </div>
            <div class="section">
                <h3 class="white center">LOGIN</h3>
                <form id="log-in" action="/magnify/web/login" method="post">
                    <span class="danger {{ errors['display'] | default('') }} center white">{{ errors['email'] | default('') }}</span>
                    <input type="text" name="email" placeholder="ENTER YOUR EMAIL" autocomplete="off" value="{{ email | default('') }}">
                    <span class="danger {{ errors['display'] | default('') }} center white">{{ errors['password'] | default('') }}</span>
                    <input type="password" name="password" placeholder="PASSWORD" autocomplete="off">
                    <input type="submit" name="log-in" value="LOGIN">
                </form>
            </div>
            <div class="section" id="signed-up"></div>
            <span class="fa fa-times close-sign-up" aria-hidden="true"></span>
        </div>
        <div id="overlay"></div>
    </header>
    {% block content %}
    
    {% endblock %}
    
    <script type="text/javascript">
        $("#sign-up-btn").click(function(event){
            event.preventDefault();
            if (!$(this).siblings('input').val()) {
                $('#sign-form-error').html('Please make sure the form is filled in correctly');
            } else {
            
            $.ajax({
                url: '/magnify/web/signup',
                method: 'POST',
                data: {regName: $('#reg-name').val(),
                      regSurname: $('#reg-surname').val(),
                      regEmail: $('#reg-email').val(),
                      regPassword: $('#reg-password').val()}
            }).done(function(data){
                $('#sign-form-error').html(data);
            }).fail(function(){
                $('#sign-form-error').html('Please make sure the form is filled in correctly');
            });
            }
        });
    </script>
</body>
</html>