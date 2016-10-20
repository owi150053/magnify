<?php
    //AUTOLOAD

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/delegates/auth_delegate.php';
    require_once __DIR__.'/delegates/dash_delegate.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;

    $app = new Silex\Application();

    $app['debug'] = false;
    
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
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            $app['session']->get('id'));
        return $app['twig']->render('home.twig', $model);
    });

    $app->post('/login', function(Request $request) use ($app){
        
        //FROM THE FORM
        $email = $request->get('email');
        $password = $request->get('password');

        //VALIDATION
        $errors = array();
        $user = get_user_by_email($email);
        
        if (isset($_POST['log-in'])) {
            if ($user == null) {
                $errors['email'] = "This email is invalid or unregistered.";
                $errors['display'] = "block error";
                $model = array('email' => $email, 'errors' => $errors);
                return $app['twig']->render('home.twig', $model);
            
            } else if (!correct_password_for_user($user, $password)) {
                $errors['password'] = "Incorrect password.";
                $errors['display'] = "block error";
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
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/login-page');
        }
        $get_user_posts = getUserPosts($app['session']->get('id'));
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
                       'email' => $app['session']->get('email'),
                       'user_posts' => $get_user_posts);
        return $app['twig']->render('dashboard.twig', $model);
        
    });

    $app->post('/settings/avatar', function(Request $request) use($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/login-page');
        }
        
        $path = $request->get('avatarFile');
        $avatar = $request->files->get('avatar-file');
        
        $avatar->move('images', $avatar->getClientOriginalName());
        $updatePath = updateAvatar($path, $app['session']->get('id'));
        $model = array('updatePath' => $updatePath, 'avatar' => $app['session']->set('/images/'.$path));
        return $app['twig']->render('avatar_update.twig', $model);
    });

    $app->post('/settings/info', function(Request $request) use ($app) {
        $id = $app['session']->get('id');
        $name = $request->get('nameC');
        $surname = $request->get('surnameC');
        $email = $request->get('emailC');
        
        updateUserInfo($id, $name, $surname, $email);
        $app['session']->set('name', $name);
        $app['session']->set('surname', $surname);
        $app['session']->set('email', $email);
        
        $model= array('name' => $name, 'surname' => $surname, 'email' => $email);
        return $app['twig']->render('update-info.twig', $model);
    });

    $app->get('/login-page', function(Request $request) use ($app) {
        $errors = array();
        $errors['display'] = "block error";
       $model = array('errors' => $errors);
        return $app['twig']->render('home.twig', $model);
    });

    $app->get('/upload', function(Request $request) use ($app) {
        if ($app['session']->get('name') == !null) {
            $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                          'email' => $app['session']->get('email'));
            return $app['twig']->render('upload.twig', $model);
        } else {
            return $app->redirect('/magnify/web/login-page');
        }
    });
$app->get('/contact-us', function(Request $request) use ($app) {
    if ($app['session']->get('name') == !null) {
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'));
        return $app['twig']->render('contact-us.twig', $model);
    } else {
        return $app->redirect('/magnify/web/login-page');
    }
});


    $app->get('/logout', function(Request $request) use ($app) {
       $app['session']->invalidate();
        return $app->redirect('/magnify/web/');
    });
    
    //RUN APP
    $app->run();

?>