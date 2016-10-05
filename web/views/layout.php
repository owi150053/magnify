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
                <li></li>
            </ul>
        </div>
        <div id="sign-up">
            <form>
                <input type="text" name="name" placeholder="ENTER YOUR NAME">
            </form>
        </div>
        <div id="overlay"></div>
    </header>
    {% block content %}
    
    {% endblock %}
</body>
</html>