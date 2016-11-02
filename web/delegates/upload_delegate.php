<?php
function uploadPost($title, $content, $image_path, $id) {
    require __DIR__.'/../seed.php';

    $sql = "INSERT INTO posts (title, content, image_path, author_id, post_date) VALUES (:title, :content, :image_path, :author_id, CURRENT_DATE);";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $statement->bindValue(':author_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $pdo = null;
}
?>