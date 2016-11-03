<?php
    function getPosts() {
        require __DIR__.'/../seed.php';

        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, posts.post_date, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
        INNER JOIN users
        ON author_id=users.id;";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    function search($search) {
        require __DIR__.'/../seed.php';

        $s = '%'.$search.'%';

        $sql = "SELECT * FROM posts
                INNER JOIN users
                ON author_id=users.id
                WHERE title LIKE :search;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':search', $s, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    function getPostDetail($id) {
        require __DIR__.'/../seed.php';

        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, posts.post_date, posts.likes, posts.dislikes, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
                INNER JOIN users
                ON author_id=users.id
                WHERE posts.id=:post_id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();

    }
?>