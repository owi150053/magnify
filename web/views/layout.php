<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ webroot }}/css/font-awesome.css">
    <link rel="stylesheet" href="{{ webroot }}/css/main.css">
    <link rel="stylesheet" href="{{ webroot }}/css/home.css">
    <link rel="stylesheet" href="{{ webroot }}/css/contact-us.css">
    <link rel="stylesheet" href="{{ webroot }}/css/upload.css">
    <link rel="stylesheet" href="{{ webroot }}/css/post.css">
    <link rel="stylesheet" href="{{ webroot }}/css/recent-posts.css">
    <link href="https://fonts.googleapis.com/css?family=Ledger" rel="stylesheet">
    <link rel="stylesheet" href="{{ webroot }}/css/fonts.css">
    
    <script src="{{ webroot }}/js/nav.js" type="text/javascript"></script>
    <script src="{{ webroot }}/js/load.js" type="text/javascript"></script>
    <script src="{{ webroot }}/js/animation.js" type="text/javascript"></script>
    <script src="{{ webroot }}/js/validation.js" type="text/javascript"></script>
    <script src="{{ webroot }}/js/file-upload.js" type="text/javascript"></script>
    <script src="{{ webroot }}/js/jquery.scrollify.js" type="text/javascript"></script>
</head>
<body>
    <div id="preload">
        <span class="fa fa-circle-o-notch fa-spin fa-3x white"></span>
        <b class="loading white">LOADING</b>
    </div>
    <header>
        <nav>
            
            {% if name is not null %}
            
            <div id="nav-avatar" style="background:url({{ webroot }}{{ avatar }}) center;background-size:cover;">
                <ul class="user-drop center">
                    <a href="{{ webroot }}/{% if admin == true %}dashboard{% else %}profile-edit{% endif %}"><li>VIEW PROFILE</li></a>
                    {% if admin == true %}
                    <a href="{{ webroot }}/upload"><li>CREATE POST</li></a>
                    {% endif %}
                    <a href="{{ webroot }}/logout"><li>SIGN OUT</li></a>
                    <span class="triangle-home"></span>
                </ul>
            </div>          
            {% elseif name is null %}
                <li id='login-btn'><span class='fa fa-user' aria-hidden='true'></span>&nbsp;LOGIN | SIGN UP</li>
            {% endif %}
            
            <li id="menu-btn"><span class="fa fa-bars menu-icon" aria-hidden="true"></span></li>
        </nav>
        <div id="side-bar">
            <span class="fa fa-times close-menu" aria-hidden="true"></span>
            <ul>
                <a href="{{ webroot }}/"><li>HOME</li></a>
                <a href="{{ webroot }}/recent-posts"><li>LATEST POSTS</li></a>
                <a href="{{ webroot }}/contact-us"><li>CONTACT US</li></a>
            </ul>
            <div class="login-logout white">
                {% if name is not null %}
                <a href="{{ webroot }}/dashboard" class="profile-menu">
                    <p><span class='fa fa-user fa-lg' aria-hidden='true'></span>&nbsp;&nbsp;&nbsp;PROFILE</p>
                </a>
                <a href="{{ webroot }}/logout" class="logout-menu">
                    <p><span class='fa fa-sign-out fa-lg' aria-hidden='true'></span>&nbsp;SIGN OUT</p>
                </a>
                {% elseif name is null %}
                <a href="{{ webroot }}/login-page" class="profile-menu black">
                    <p><span class='fa fa-user fa-lg' aria-hidden='true'></span>&nbsp;&nbsp;&nbsp;SIGN UP</p>
                </a>
                <a href="{{ webroot }}/login-page" class="logout-menu">
                    <p><span class='fa fa-sign-out fa-lg' aria-hidden='true'></span>&nbsp;LOG IN</p>
                </a>
                {% endif %}
            </div>
        </div>
        <div class="sign-up-modal animateZoom {{ errors['display'] | default('') }} {{ errors['sign-display'] | default('') }}">
            <div id="signup-suc" class="section">
                
                <h3 class="white center">SIGN UP</h3>
                <form id="sign-up" action="{{ webroot }}/signup" method="post">
                    <span class="errN danger none center white">Name must be longer than 2 characters</span>
                    <input type="text" id="reg-name" name="reg-name" placeholder="ENTER YOUR NAME" autocomplete="off">
                    <span class="errS danger none center white">Surname must be longer than 2 characters</span>
                    <input type="text" id="reg-surname" name="reg-surname" placeholder="ENTER YOUR SURNAME" autocomplete="off">
                    <span class="danger {{ errors['sign-display'] | default('') }} center white">{{ errors['email-exists'] | default('') }}</span>
                    <span class="errE danger none center white"> Please enter a valid email</span>
                    <input type="text" id="reg-email" name="reg-email" placeholder="ENTER YOUR EMAIL" autocomplete="off">
                    <span class="errP danger none center white">Please make sure your passwoord is longer then 8 characters</span>
                    <input type="password" id="reg-password" name="reg-password" placeholder="CHOOSE A PASSWORD" autocomplete="off">
                    <input type="submit" id="sign-up-btn" name="sign-up" value="SIGN UP">
                    <span id="sign-form-error" class="danger center error block">{{ errors['form'] | default('') }}</span>
                </form>
            </div>
            <div class="section">
                <h3 class="white center">LOGIN</h3>
                <form id="log-in" action="{{ webroot }}/login" method="post">
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
                url: '{{ webroot }}/signup',
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