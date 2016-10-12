<?php

    function getUserPosts($id) {
        require __DIR__.'../seed.php';
        
        $sql = "SELECT * FROM posts WHERE id=:author_id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':author_id', $id PDO::PARAM_INT);
        $statement->execute();
        $result = $statemnet->fetchAll();
        
    }

?>