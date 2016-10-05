<?php
    //AUTOLOAD
    //TESTING PHPstorm
    require_once __DIR__.'/../vendor/autoload.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    $app = new Silex\Application();
    
    //SERVICE PROVIDER
    $app->register(new Silex\Provider\SessionServiceProvider());
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    //ROUTES
    $app->get('/', function(Request $request) use ($app) {
        return $app['twig']->render('home.twig', array());
    });
    
    //RUN APP
    $app->run();

?>