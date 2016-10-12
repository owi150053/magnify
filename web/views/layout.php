<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="/magnify/web/css/font-awesome.css">
    <link rel="stylesheet" href="/magnify/web/css/main.css">
    <link rel="stylesheet" href="/magnify/web/css/home.css">
    <link href="https://fonts.googleapis.com/css?family=Ledger" rel="stylesheet">
    
    <script src="/magnify/web/js/nav.js" type="text/javascript"></script>
    <script src="/magnify/web/js/load.js" type="text/javascript"></script>
</head>
<body>
    <div id="preload">
        <span class="fa fa-circle-o-notch fa-spin fa-3x white"></span>
        <b class="loading white">LOADING</b>
    </div>
    <header>
        <nav>
            {% if name is not null %}
            <li class="black inline upper hello">Hi, <a class="underline black" href="/magnify/web/dashboard">{{ name }}</a> | </li>
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
        <div class="sign-up-modal animateZoom {{ errors['display'] }}">
            <div class="section">
                <h3 class="white center">SIGN UP</h3>
                <form id="sign-up">
                    <input type="text" name="reg-name" placeholder="ENTER YOUR NAME" autocomplete="off">
                    <input type="text" name="reg-email" placeholder="ENTER YOUR EMAIL" autocomplete="off">
                    <input type="password" name="reg-password" placeholder="CHOOSE A PASSWORD" autocomplete="off">
                    <input type="submit" name="sign-up" value="SIGN UP">
                </form>
            </div>
            <div class="section">
                <h3 class="white center">LOGIN</h3>
                <form id="log-in" action="/magnify/web/login" method="post">
                    <span class="danger {{ errors['display'] }} center white">{{ errors['email'] }}</span>
                    <input type="text" name="email" placeholder="ENTER YOUR EMAIL" autocomplete="off" value="{{ email }}">
                    <span class="danger {{ errors['display'] }} center white">{{ errors['password'] }}</span>
                    <input type="password" name="password" placeholder="CHOOSE A PASSWORD" autocomplete="off">
                    <input type="submit" name="log-in" value="LOGIN">
                </form>
            </div>
            <span class="fa fa-times close-sign-up" aria-hidden="true"></span>
        </div>
        <div id="overlay"></div>
    </header>
    {% block content %}
    
    {% endblock %}
</body>
</html>