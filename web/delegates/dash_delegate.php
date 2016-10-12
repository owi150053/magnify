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
        
        foreach ($results as $row) {
            $post_id = $row['id'];
            $post_title = $row['title'];
            $post_content = $row['content'];
            $post_image = $row['image_path'];
            $user_name = $row['name'];
            $user_surname = $row['surname'];
            $content = "<div class='content-block'>
             <h2>$post_title</h2>
             <p>$post_content</p>
             </div>";
        }
        
    }

?>