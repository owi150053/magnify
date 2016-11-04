<?php
    //AUTOLOAD

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/delegates/auth_delegate.php';
    require_once __DIR__.'/delegates/dash_delegate.php';
    require_once __DIR__.'/delegates/upload_delegate.php';
    require_once __DIR__.'/delegates/post_delegate.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;

    $app = new Silex\Application();

    $app['debug'] = true;
    
    //SERVICE PROVIDER
    $app->register(new Silex\Provider\SessionServiceProvider());
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app['webroot'] = getenv('WEBROOT');
    if ($app['webroot'] == false ) {
        $app['webroot'] = '/magnify/web';
    }
    $app['twig']->addGlobal('webroot', $app['webroot']);

    //BEFORE
    $app->before(function(Request $request) {
        $request->getSession()->start();
    });

    //ROUTES
    $app->get('/', function(Request $request) use ($app) {
        $admin = checkIfAdmin($app['session']->get('admin'));
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            $app['session']->get('id'),
                      'admin' => $admin);
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
                $app['session']->set('ban', $user['ban']);
                
                $admin = checkIfAdmin($app['session']->get('admin'));
                if ($admin == true) {
                    return $app->redirect($app['webroot'].'/dashboard');
                } else {
                    return $app->redirect($app['webroot'].'/profile-edit');
                }
            }
        }
        
    });

    $app->post('/signup', function(Request $request) use ($app) {
        
        $name = $request->get('regName');
        $surname = $request->get('regSurname');
        $email = $request->get('regEmail');
        $password_hash = $request->get('regPassword');
        $avatar_path = "/images/default-pp.jpg";
        $admin = 0;
        $ban = 0;

        $checkEmail = get_user_by_email($email);
        
        $errors = array();
        
        
        if ($checkEmail == null && !empty($email)) {
                createUser($name, $surname, $email, $avatar_path, $password_hash, $admin, $ban);
                $user = get_user_by_email($email);
                $app['session']->set('id', $user['id']);
                $app['session']->set('name', $user['name']);
                $app['session']->set('surname', $user['surname']);
                $app['session']->set('email', $user['email']);
                $app['session']->set('avatar', $user['avatar_path']);
                
                $app['session']->set('admin', $user['admin']);
                $app['session']->set('ban', $user['ban']);

                $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'),
                    'ban' => $app['session']->get('ban'));
                
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
        $admin = checkIfAdmin($app['session']->get('admin'));
        $ban = checkIfBanned($app['session']->get('ban'));
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }
        if ($admin == false) {
            return $app->redirect($app['webroot'].'/profile-edit');
        }

        if ($ban == true) {
            return $app->redirect($app['webroot'].'/banned');
        }
        $getUsers = getUsers();
        $get_user_posts = getUserPosts($app['session']->get('id'));
        $admin = checkIfAdmin($app['session']->get('admin'));
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'user_posts' => $get_user_posts,
            'admin' => $admin,
            'users' => $getUsers,
            'ban' => $ban);
        
        return $app['twig']->render('dashboard.twig', $model);
        
    });

    $app->get('/profile-edit', function(Request $request) use ($app){
        $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'),
            'ban' => $app['session']->get('ban'));
        return $app['twig']->render('profile-edit.twig', $model);
    });

    $app->post('/ban', function(Request $request) use ($app) {
        $id = $request->get('ban-id');
        $banVal = $request->get('ban-val');
        banUser($id, $banVal);
        return $app->redirect($app['webroot'].'/dashboard');
    });

    $app->post('/auth', function(Request $request) use ($app) {
        $id = $request->get('admin-id');
        $adminVal = $request->get('admin-val');
        makeAdmin($id, $adminVal);
        return $app->redirect($app['webroot'].'/dashboard');
    });

    $app->post('/settings/avatar', function(Request $request) use($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }
        $avatarFile = $request->files->get('file');
        
        if ($avatarFile == !null) {
        
        $avatarFile->move('images', $avatarFile->getClientOriginalName());
        $id = $app['session']->get('id');

        $path = "/images/".$avatarFile->getClientOriginalName();
        updateAvatar($path, $id);
        $app['session']->set('avatar', $path);
        return $app->redirect($app['webroot'].'/dashboard');
        } else {
            $model = array('error' => 'Please select a file', 'display' => 'block error',
                          'name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'),
                'ban' => $app['session']->get('ban'));
            return $app['twig']->render('profile-edit.twig', $model);
        }
    });

    $app->post('/update-profile', function(Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }
        $avatarFile = $request->files->get('file');
        $name = $request->get('name');
        $surname = $request->get('surname');
        $email = $request->get('email');
        $id = $app['session']->get('id');

        if ($avatarFile == !null) {
            $avatarFile->move('images', $avatarFile->getClientOriginalName());
            $id = $app['session']->get('id');

            $path = "/images/".$avatarFile->getClientOriginalName();
            updateAvatar($path, $id);
            $app['session']->set('avatar', $path);
        }

        if ($name == !null && $surname == !null && $email == !null) {

            updateUserInfo($id, $name, $surname, $email);


            $app['session']->set('name', $name);
            $app['session']->set('surname', $surname);
            $app['session']->set('email', $email);
            $model = array('error' => 'Thank you for updating!', 'display' => 'block',
                'name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'),
                'ban' => $app['session']->get('ban'));
            return $app['twig']->render('profile-edit.twig', $model);
        }
        if ($name == null || $surname == null || $email == null) {
            $model = array('error' => 'Please make sure the form is filled in correctly', 'display' => 'block danger error',
                'name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $app['session']->get('admin'),
                'ban' => $app['session']->get('ban'));
            return $app['twig']->render('profile-edit.twig', $model);
        }
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
        $ban = checkIfBanned($app['session']->get('ban'));
        if ($ban == true) {
            return $app->redirect($app['webroot'].'/banned');
        }
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }

        $admin = checkIfAdmin($app['session']->get('admin'));
            $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $admin,
                'ban' => $app['session']->get('ban'));
            if ($admin == true) {
                return $app['twig']->render('upload.twig', $model);
            } if ($admin == false) {
                return $app->redirect($app['webroot'].'/contact-us');
            }
    });

    $app->get('/contact-us', function(Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/guest-contact-us');
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
        return $app->redirect($app['webroot'].'/');
    });

    $app->post('/upload/post', function(Request $request) use ($app) {
        $ban = checkIfBanned($app['session']->get('ban'));
        if ($ban == true) {
            return $app->redirect($app['webroot'].'/banned');
        }
        $admin = checkIfAdmin($app['session']->get('admin'));
        $title = $request->get('post-title');
        $content = $request->get('upload-text');
        $id = $app['session']->get('id');


        $avatarFile = $request->files->get('header-image-file');

        if ($avatarFile == !null && $title == !null && $content == !null) {
            $avatarFile->move('images/content', $avatarFile->getClientOriginalName());
            $image_path = "/images/content/" . $avatarFile->getClientOriginalName();

            uploadPost($title, $content, $image_path, $id);

            return $app->redirect($app['webroot'].'/recent-posts');
        } else {
            $admin = checkIfAdmin($app['session']->get('admin'));
            $error = "Please make sure the form is filled in correctly";
            $model = array('name' => $app['session']->get('name'),
                'surname' => $app['session']->get('surname'),
                'avatar' => $app['session']->get('avatar'),
                'id' => $app['session']->get('id'),
                'email' => $app['session']->get('email'),
                'admin' => $admin,
                'error' => $error);
            if ($admin == true) {
                return $app['twig']->render('upload.twig', $model);
            } if ($admin == false) {
                return $app->redirect($app['webroot'].'/contact-us');
            }
        }
    });

    $app->get('/recent-posts', function(Request $request) use ($app) {
        $ban = checkIfBanned($app['session']->get('ban'));
        if ($ban == true) {
            return $app->redirect($app['webroot'].'/banned');
        }
        $admin = checkIfAdmin($app['session']->get('admin'));
        $getPosts = getPosts();
        $totalLikes = getTotalLikes($getPosts['post_id']);
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'admin' => $admin,
            'posts' => $getPosts,
            'likes' => $totalLikes);
        return $app['twig']->render('recent_posts.twig', $model);
    });

    $app->post('/view', function(Request $request) use ($app) {
        $admin = checkIfAdmin($app['session']->get('admin'));
        $id = $request->get('postid');
        $postD = getPostDetail($id);
        $totalLikes = getTotalLikes($id);
        $totalDislikes = getTotalDislikes($id);
        $getComments = getComments($post_id);
          $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'admin' => $admin,
              'post' => $postD,
              'likes' => $totalLikes,
              'dislikes' => $totalDislikes,
          'comments' => $getComments);
        return $app['twig']->render('post.twig', $model);

    });

    $app->post('/search', function (Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }
        $searchTxt = $request->get('search');
        $result = search($searchTxt);
        $model = array('results' => $result);

        return $app['twig']->render('search-results.twig', $model);
    });

    $app->post('/search/user', function (Request $request) use ($app) {
        if (!$app['session']->has('id')) {
            return $app->redirect($app['webroot'].'/login-page');
        }
        $searchTxt = $request->get('search');
        $result = searchUser($searchTxt);

        $model = array('results' => $result);

        return $app['twig']->render('user-search.twig', $model);
    });

    $app->post('/like', function(Request $request) use ($app) {

        $user_like = $request->get('like');
        $user_id = $app['session']->get('id');
        $post_id = $request->get('postId');
        $postD = getPostDetail($post_id);
        $getLike = getLike($post_id, $user_id);
        if ($getLike >= 1) {
            removeLike($post_id, $user_id);
            $totalLikes = getTotalLikes($post_id);
            $totalDislikes = getTotalDislikes($post_id);
            $model = array('post' => $postD,
                'likes' => $totalLikes,
                'dislikes' => $totalDislikes);
            return $app['twig']->render('like.twig', $model);
        } elseif ($getLike < 1) {
            updateLike($post_id, $user_id, $user_like);
            $totalLikes = getTotalLikes($post_id);
            $totalDislikes = getTotalDislikes($post_id);
            $model = array('post' => $postD,
                'likes' => $totalLikes,
                'dislikes' => $totalDislikes);
            return $app['twig']->render('like.twig', $model);
        }
    });

    $app->post('/dislike', function(Request $request) use ($app) {

        $user_like = $request->get('dislike');
        $user_id = $app['session']->get('id');
        $post_id = $request->get('postId');
        $postD = getPostDetail($post_id);
        $getLike = getLike($post_id, $user_id);
        $model = array('post' => $postD);
        if ($getLike >= 1) {
            removeLike($post_id, $user_id);
            return $app['twig']->render('like.twig',$model);
        } elseif ($getLike < 1) {
            updateLike($post_id, $user_id, $user_like);
            return $app['twig']->render('like.twig', $model);
        }
    });



    $app->get('/banned', function(Request $request) use ($app) {
        $admin = checkIfAdmin($app['session']->get('admin'));
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'admin' => $admin,
            'ban' => $app['session']->get('ban'));
        return $app['twig']->render('banned.twig', $model);
    });

    $app->post('/comment', function(Request $request) use ($app){
        $user_id = $request->get("user-id");
        $post_id = $request->get("post-id");
        $comment_text = $request->get("comment-text");
        postComment($post_id, $user_id, $comment_text);
        $postD = getPostDetail($post_id);
        $totalLikes = getTotalLikes($post_id);
        $totalDislikes = getTotalDislikes($post_id);
        $getComments = getComments($post_id);
        $model = array('name' => $app['session']->get('name'),
            'surname' => $app['session']->get('surname'),
            'avatar' => $app['session']->get('avatar'),
            'id' => $app['session']->get('id'),
            'email' => $app['session']->get('email'),
            'admin' => $admin,
            'post' => $postD,
            'likes' => $totalLikes,
            'dislikes' => $totalDislikes,
            'comments' => $getComments);

        return $app['twig']->render("post.twig", $model);
});

    
    //RUN APP
    $app->run();

?>