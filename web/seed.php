<?php
    
    $host = 'localhost:8888';
    $dbname = 'magnify'; 

    // Note the dsn doesn't include a dbname
    $dsn = "mysql:host=$host;charset=utf8";
    $username = "root";
    $password = "root";
    
    try {
        $pdo = new PDO($dsn, $username, $password);
        
        // Create the database if necessary
        $statement = $pdo->prepare("CREATE DATABASE IF NOT EXISTS $dbname;");
        $statement->execute();
        $pdo->query("use $dbname;");
        
        // Create the user table if necessary
        $statement = $pdo->prepare("CREATE TABLE IF NOT EXISTS users (
            id int NOT NULL AUTO_INCREMENT,
            name varchar(255),
            surname varchar(255),
            email varchar(255),
            avatar_path varchar(255),
            password_hash varchar(32),
            PRIMARY KEY (id)
        );");
        $statement->execute();
        
        // Add some users if the table is empty
        $statement = $pdo->prepare("SELECT COUNT(*) FROM users;");
        $statement->execute();
        $numUsers = $statement->fetchColumn();
        if ($numUsers == 0) {
            $sql = "INSERT INTO users (name, surname, email, avatar_path, password_hash)
                    VALUES (:name, :surname, :email, :avatar_path, :password_hash);";
            $statement = $pdo->prepare($sql);

            $statement->bindValue(':name', 'Roger', PDO::PARAM_STR);
            $statement->bindValue(':surname', 'Ballen', PDO::PARAM_STR);
            $statement->bindValue(':email', 'roger@ballen.com', PDO::PARAM_STR);
            $statement->bindValue(':avatar_path', '/images/roger.jpg', PDO::PARAM_STR);
            $statement->bindValue(':password_hash', md5('roger'), PDO::PARAM_STR);
            $statement->execute();

            $statement->bindValue(':name', 'Annie', PDO::PARAM_STR);
            $statement->bindValue(':surname', 'Leibovitz', PDO::PARAM_STR);
            $statement->bindValue(':email', 'annie@leibovitz.com', PDO::PARAM_STR);
            $statement->bindValue(':avatar_path', '/images/annie.jpg', PDO::PARAM_STR);
            $statement->bindValue(':password_hash', md5('annie'), PDO::PARAM_STR);
            $statement->execute();
        }
    } catch (PDOException $exception) {
        echo 'Database error: ' . $exception->getMessage();
    }
?>