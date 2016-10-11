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
</head>
<body>
    <header>
        <nav>
            <li id="login-btn"><span class="fa fa-user" aria-hidden="true"></span>&nbsp;LOGIN | SIGN UP</li>
            <li id="menu-btn"><span class="fa fa-bars menu-icon" aria-hidden="true"></span></li>
        </nav>
        <div id="side-bar">
            <span class="fa fa-times close-menu" aria-hidden="true"></span>
            <ul>
                <li>HOME</li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="sign-up-modal animateZoom">
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
                    <span class="error">{{ errors['email'] }}</span>
                    <input type="text" name="email" placeholder="ENTER YOUR EMAIL" autocomplete="off" value="{{ email }}">
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