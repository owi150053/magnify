<?php
    //AUTOLOAD

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/delegates/auth_delegate.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;

    $app = new Silex\Application();
    
    //SERVICE PROVIDER
    $app->register(new Silex\Provider\SessionServiceProvider());
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    //BEFORE
    $app->before(function(Request $request) {
        $request->getSession()->start();
    });

    //ROUTES
    $app->get('/', function(Request $request) use ($app) {
        return $app['twig']->render('home.twig', array());
    });

    $app->post('/', function(Request $request) use ($app){
        
        //FROM THE FORM
        $email = $request->get('email');
        $password = $request->get('password');

        //VALIDATION
        $errors = array();
        $user = get_user_by_email($email);
        
        if (isset($_POST['log-in'])) {
            if ($user == null) {
                $errors['email'] = "This email is invalid or unregistered.";
                $model = array('email' => $email, 'errors' => $errors);
                return $app['twig']->render('home.twig', $model);
            
            } else if (!correct_password_for_user($user, $password)) {
                $errors['password'] = "Incorrect password.";
                $model = array('email' => $email, 'errors' => $errors);
                return $app['twig']->render('home.twig', $model);
                
            } else {
                $app['session']->set('id', $user['id']);
                $app['session']->set('name', $user['name']);
                $app['session']->set('surname', $user['surname']);
                $app['session']->set('email', $user['email']);
                $app['session']->set('avatar', $user['avatar_path']);
                return $app->redirect('/magnify/web/dashboard');
            }
        }
        
    });

    $app->get('/dashboard', function(Request $request) use ($app) {
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar_path' => $app['session']->get('avatar_path'),
            $app['session']->get('id'));
        return $app['twig']->render('dashboard.twig', $model);
    });

    $app->get('/upload', function(Request $request) use ($app) {
        return $app['twig']->render('upload.twig', array());
    });

    $app->get('/logout', function(Request $request) use ($app) {
       $app['session']->invalidate();
        return $app->redirect('/magnify/web/');
    });
    
    //RUN APP
    $app->run();

?>