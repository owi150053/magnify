<?php
function uploadPost($title, $content, $id) {
    require __DIR__.'/../seed.php';

    $sql = "INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id);";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':author_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $pdo = null;
}
?>