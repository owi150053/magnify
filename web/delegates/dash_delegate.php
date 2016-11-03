<?php

    function getUserPosts($id) {
        require __DIR__.'/../seed.php';
        
        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
        INNER JOIN users
        ON author_id=users.id
        WHERE author_id = :author_id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':author_id', $id, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        
        return $results;
        
    }

    function updateAvatar($path, $id) {
        require __DIR__.'/../seed.php';        
        
        $sql = "UPDATE users SET avatar_path=:path WHERE id=:id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':path', $path, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        
        $pdo = null;
    }

    function updateUserInfo($id, $name, $surname, $email) {
        require __DIR__.'/../seed.php';
        
        if (empty($name) || empty($surname) || empty($email)) {
            echo "Please complete all form fields";
                
        } else {
        
            $sql = "UPDATE users SET name=:name, surname=:surname, email=:email WHERE id=:id;";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':name', $name, PDO::PARAM_STR);
            $statement->bindValue(':surname', $surname, PDO::PARAM_STR);
            $statement->bindValue(':email', $email, PDO::PARAM_STR);

            $statement->execute();

            $pdo = null;
            
        }
    }

    function checkIfAdmin($admin) {        
        if ($admin == 1) {
            return true;
        } elseif ($admin == 0){
            return false;
        }
    }

    function makeAdmin($id, $admin) {
        require __DIR__.'/../seed.php';
        if ($admin == 0) {
            $sql = "UPDATE users SET admin=1 WHERE id=:id;";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

        } elseif ($admin == 1) {
            $sql = "UPDATE users SET admin=0 WHERE id=:id;";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

        }
    }

    function checkIfBanned($ban) {
        if ($ban == 1) {
            return true;
        } elseif ($ban == 0){
            return false;
        }
    }

    function banUser($id, $ban) {
        require __DIR__.'/../seed.php';
        if ($ban == 0) {
            $sql = "UPDATE users SET ban=1 WHERE id=:id;";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

        } elseif ($ban == 1) {
            $sql = "UPDATE users SET ban=0 WHERE id=:id;";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

        }
    }

    function getUsers() {
        require __DIR__.'/../seed.php';

        $sql = "SELECT * FROM users";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        return $result;
    }

function searchUser($search) {
    require __DIR__.'/../seed.php';

    $s = '%'.$search.'%';

    $sql = "SELECT users.name, users.surname, users.email FROM users
                WHERE title LIKE :search;";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':search', $s, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetchAll();
}

?>