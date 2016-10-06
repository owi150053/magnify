<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="/magnify/web/css/font-awesome.css">
    <link rel="stylesheet" href="/magnify/web/css/main.css">
    <link rel="stylesheet" href="/magnify/web/css/dash.css">
    <link href="https://fonts.googleapis.com/css?family=Ledger" rel="stylesheet">
    
    <script src="/magnify/web/js/nav.js" type="text/javascript"></script>
</head>
<body>
    <header>
        <nav class="white">
            <li id="logout-btn" class="dash-right"><span class="fa fa-sign-out" aria-hidden="true"></span>&nbsp;SIGN OUT</li>
            <li id="menu-btn" class="dash-right"><span class="fa fa-bars right menu-icon black" aria-hidden="true"></span></li>
            <li class="user-name center">NAME</li>
        </nav>
        <div id="dash-side-bar">
            <img class="avatar">
            <ul class="center white">
                <li>TOTAL POSTS</li>
                <li>TOTAL FRIENDS</li>
                <li>TOTAL LIKES</li>
            </ul>
        </div>
        <div id="side-bar">
            <span class="fa fa-times close-menu" aria-hidden="true"></span>
            <span class="fa fa-sign-out sign-out-menu" aria-hidden="true"></span>
            <ul>
                <li>HOME</li>
                <li></li>
                <li></li>
            </ul>
        </div>
        
        <div id="overlay"></div>
    </header>
    {% block content %}
    
    {% endblock %}
</body>
</html>