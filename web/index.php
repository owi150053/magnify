<?php
    //AUTOLOAD

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/delegates/auth_delegate.php';
    require_once __DIR__.'/delegates/dash_delegate.php';
    require_once __DIR__.'/delegates/upload_delegate.php';

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
                $app['session']->set('admin', $user['admin']);
                return $app->redirect('/magnify/web/dashboard');
            }
        }
        
    });

    $app->post('/signup', function(Request $request) use ($app) {
        
        $name = $request->get('regName');
        $surname = $request->get('regSurname');
        $email = $request->get('regEmail');
        $password_hash = $request->get('regPassword');
        $avatar_path = "";
        $admin = 0;

        $checkEmail = get_user_by_email($email);
        
        $errors = array();
        
        
        if ($checkEmail == null && !empty($email)) {
                createUser($name, $surname, $email, $avatar_path, $password_hash, $admin);
                $user = get_user_by_email($email);
                $app['session']->set('id', $user['id']);
                $app['session']->set('name', $user['name']);
                $app['session']->set('surname', $user['surname']);
                $app['session']->set('email', $user['email']);
                $app['session']->set('avatar', $user['avatar_path']);
                
                $app['session']->set('admin', $user['admin']);
                
                $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'));
                
                return $app['twig']->render('registered.twig', $model);
                
            } 
        
        if (empty($email) || empty($name) || empty($surname) || empty($password_hash)) {
                
                return "Please make sure all the fields are filled in";
                
            } 
        
        if ($checkEmail == !null) {
                return "The email chosen is already registered";
            }
        
    });

    $app->get('/dashboard', function(Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/login-page');
        }
        $get_user_posts = getUserPosts($app['session']->get('id'));
        $admin = checkIfAdmin($app['session']->get('admin'));
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'user_posts' => $get_user_posts,
            'admin' => $admin);
        
        return $app['twig']->render('dashboard.twig', $model);
        
    });

    $app->get('/profile-edit', function(Request $request) use ($app){
        $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'));
        return $app['twig']->render('profile-edit.twig', $model);
    });

    $app->post('/settings/avatar', function(Request $request) use($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/login-page');
        }
        
        $avatarFile = $request->files->get('insert-avatar');
        
        $avatarFile->move('images', $avatar->getClientOriginalName());
        return $app->redirect('/magnify/web/dashboard');
    });

    $app->post('/settings/info', function(Request $request) use ($app) {
        $id = $app['session']->get('id');
        $name = $request->get('nameC');
        $surname = $request->get('surnameC');
        $email = $request->get('emailC');

        $info = updateUserInfo($id, $name, $surname, $email);
        $app['session']->set('name', $name);
        $app['session']->set('surname', $surname);
        $app['session']->set('email', $email);
        $model = array('info' => $info);
        
        return $app['twig']->render('update-info.twig', $model);
    });

    $app->get('/login-page', function(Request $request) use ($app) {
        $errors = array();
        $errors['display'] = "block error";
       $model = array('errors' => $errors);
        return $app['twig']->render('home.twig', $model);
    });

    $app->get('/upload', function(Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/login-page');
        }
        $admin = checkIfAdmin($app['session']->get('admin'));
            $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $admin);
            if ($admin == true) {
                return $app['twig']->render('upload.twig', $model);
            } if ($admin == false) {
                return $app->redirect('/magnify/web/contact-us');
            }
    });

    $app->get('/contact-us', function(Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect('/magnify/web/guest-contact-us');
        }
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'));
        return $app['twig']->render('contact-us.twig', $model);
    });

    $app->get('/restricted', function(Request $request) use ($app){
        return $app['twig']->render('guest-contact-us.twig', array());
    });
    
    $app->get('/guest-contact-us', function(Request $request) use ($app) {
            return $app['twig']->render('guest-contact-us.twig', array());
        
    });


    $app->get('/logout', function(Request $request) use ($app) {
       $app['session']->invalidate();
        return $app->redirect('/magnify/web/');
    });

    $app->post('/upload/post', function(Request $request) use ($app) {
        $title = $request->get('post-title');
        $content = $request->get('upload-text');
        $id = $app['session']->get('id');

        uploadPost($title, $content, $id);

        return $app->redirect('/magnify/web/dashboard');
    });

    $app->get('/post', function(Request $request) use ($app) {
        return $app['twig']->render('post.twig', array());

    });

    
    //RUN APP
    $app->run();

?>