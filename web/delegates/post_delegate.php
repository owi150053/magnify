<?php
    function getPosts() {
        require __DIR__.'/../seed.php';

        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
        INNER JOIN users
        ON author_id=users.id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':author_id', $id, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }
?>