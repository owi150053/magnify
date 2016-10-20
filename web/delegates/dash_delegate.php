<?php

    function getUserPosts($id) {
        require __DIR__.'/../seed.php';
        
        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
        INNER JOIN users
        ON author_id=users.id
        WHERE author_id = :author_id";
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
        $statement->bindValue(':path', '/images/'.$path, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        
        $pdo = null;
    }

    function updateUserInfo($id, $name, $surname, $email) {
        require __DIR__.'/../seed.php';
        
        $sql = "UPDATE users SET name=:name, surname=:surname, email=:email WHERE id=:id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':surname', $surname, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        
        $statement->execute();
        
        $pdo = null;
    }

?>