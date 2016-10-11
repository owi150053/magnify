<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="/magnify/web/css/font-awesome.css">
    <link rel="stylesheet" href="/magnify/web/css/main.css">
    <link rel="stylesheet" href="/magnify/web/css/dash.css">
    <link rel="stylesheet" href="/magnify/web/css/upload.css">
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
        <nav class="white">
            <li id="logout-btn" class="dash-right"><a href="/magnify/web/logout"><span class="fa fa-sign-out" aria-hidden="true"></span>&nbsp;SIGN OUT</a></li>
            <li id="menu-btn" class="dash-right"><span class="fa fa-bars right menu-icon black" aria-hidden="true"></span></li>
        </nav>
        <div id="dash-side-bar">
            <span class="avatar" style="background:url(/magnify/web{{ avatar }}) center;background-size:cover;"></span>
            <ul class="center white">
                <li>{{ name }} {{ surname }}</li>
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