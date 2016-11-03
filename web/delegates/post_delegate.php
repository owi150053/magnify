<?php
    function getPosts() {
        require __DIR__.'/../seed.php';

        $sql = "SELECT posts.id, posts.title, posts.content, posts.image_path, posts.author_id, posts.post_date, users.name, users.surname, users.avatar_path, users.id as user_id FROM posts
        INNER JOIN users
        ON author_id=users.id";
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

    function getLike($post_id, $user_id) {
        require __DIR__.'/../seed.php';

        $sql = "SELECT * FROM likes WHERE post_id=:post_id AND user_id=:user_id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetch();

        return $results;
    }

    function getTotalLikes($post_id) {
        require __DIR__.'/../seed.php';

        $sql = "SELECT * FROM likes WHERE post_id=:post_id AND user_like=1";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $results = $statement->fetchAll();

        return $results;
    }

    function getTotalDislikes($post_id) {
        require __DIR__.'/../seed.php';

        $sql = "SELECT * FROM likes WHERE post_id=:post_id AND user_like=0";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $results = $statement->fetchAll();

        return $results;
    }

    function removeLike($post_id, $user_id) {
        require __DIR__.'/../seed.php';

        $sql = "DELETE FROM likes WHERE post_id=:post_id AND user_id=:user_id;";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    }

    function updateLike($post_id, $user_id, $user_like) {
        require __DIR__.'/../seed.php';

        $sql = "INSERT INTO likes (post_id, user_id, user_like) VALUES(:post_id, :user_id, :user_like) ";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':user_like', $user_like, PDO::PARAM_INT);
        $statement->execute();

        $pdo = null;
    }
?>