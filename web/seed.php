<?php
    
    $host = 'localhost:8889';
    $dbname = 'magnify';
    $username = "root";
    $password = "root";

if (getenv('CLEARDB_DATABASE_URL')) {
    $host = getenv('DATABASE_HOST');
    $dbname = getenv('DATABASE_NAME');
    $username = getenv('DATABASE_USERNAME');
    $password = getenv('DATABASE_PASSWORD');
}

$dsn = "mysql:host=$host;charset=utf8";
    
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
            admin int,
            ban int,
            PRIMARY KEY (id)
        );");
        $statement->execute();
        
        // Add some users if the table is empty
        $statement = $pdo->prepare("SELECT COUNT(*) FROM users;");
        $statement->execute();
        $numUsers = $statement->fetchColumn();
        if ($numUsers == 0) {
            $sql = "INSERT INTO users (name, surname, email, avatar_path, password_hash, admin, ban)
                    VALUES (:name, :surname, :email, :avatar_path, :password_hash, :admin, :ban);";
            $statement = $pdo->prepare($sql);

            $statement->bindValue(':name', 'Roger', PDO::PARAM_STR);
            $statement->bindValue(':surname', 'Ballen', PDO::PARAM_STR);
            $statement->bindValue(':email', 'roger@ballen.com', PDO::PARAM_STR);
            $statement->bindValue(':avatar_path', '/Images/roger.jpg', PDO::PARAM_STR);
            $statement->bindValue(':password_hash', md5('roger'), PDO::PARAM_STR);
            $statement->bindValue(':admin', 1, PDO::PARAM_INT);
            $statement->bindValue(':ban', 0, PDO::PARAM_INT);
            $statement->execute();

            $statement->bindValue(':name', 'Annie', PDO::PARAM_STR);
            $statement->bindValue(':surname', 'Leibovitz', PDO::PARAM_STR);
            $statement->bindValue(':email', 'annie@leibovitz.com', PDO::PARAM_STR);
            $statement->bindValue(':avatar_path', '/Images/annie.jpg', PDO::PARAM_STR);
            $statement->bindValue(':password_hash', md5('annie'), PDO::PARAM_STR);
            $statement->bindValue(':admin', 0, PDO::PARAM_INT);
            $statement->bindValue(':ban', 0, PDO::PARAM_INT);
            $statement->execute();
        }
        
        $statement = $pdo->prepare("CREATE TABLE IF NOT EXISTS posts (
            id int NOT NULL AUTO_INCREMENT,
            title varchar(255),
            content longtext,
            image_path varchar(255),
            author_id int,
            post_date DATE,
            likes int,
            dislikes int,
            PRIMARY KEY (id)
        );");
        $statement->execute();
        
        $statement = $pdo->prepare("SELECT COUNT(*) FROM posts;");
        $statement->execute();
        $postCount = $statement->fetchColumn();
        if ($postCount == 0) {
            $sql = "INSERT INTO posts (title, content, image_path, author_id, post_date, likes, dislikes)
                    VALUES (:title, :content, :image_path, :author_id, CURRENT_DATE, :likes, :dislikes );";
            $statement = $pdo->prepare($sql);

            $statement->bindValue(':title', 'Lorem Ipsum', PDO::PARAM_STR);
            $statement->bindValue(':content', 'One of the most influential and important photographic artists of the 21st century, Roger Ballen’s photographs span over forty years.  His strange and extreme works confront the viewer and challenge them to come with him on a journey into their own minds as he explores the deeper recesses of his own.

Roger Ballen was born in New York in 1950 but for over 30 years he has lived and worked in South Africa. His work as a geologist took him out into the countryside and led him to take up his camera and explore the hidden world of small South African towns. At first he explored the empty streets in the glare of the midday sun but, once he had made the step of knocking on people’s doors, he discovered a world inside these houses which was to have a profound effect on his work. These interiors with their distinctive collections of objects and the occupants within these closed worlds took his unique vision on a path from social critique to the creation of metaphors for the inner mind. After 1994 he no longer looked to the countryside for his subject matter finding it closer to home in Johannesburg.

Over the past thirty years his distinctive style of photography has evolved using a simple square format in stark and beautiful black and white. In the earlier works in the exhibition his connection to the tradition of documentary photography is clear but through the 1990s he developed a style he describes as ‘documentary fiction’. After 2000 the people he first discovered and documented living on the margins of South African society increasingly became a cast of actors working with Ballen in the series’ Outland and Shadow Chamber collaborating to create powerful psychodramas.

', PDO::PARAM_STR);
            $statement->bindValue(':image_path', '/Images/content/roger_ballen.jpg', PDO::PARAM_STR);
            $statement->bindValue(':author_id', 1, PDO::PARAM_INT);
            $statement->bindValue(':likes', 153, PDO::PARAM_INT);
            $statement->bindValue(':dislikes', 4, PDO::PARAM_INT);
            $statement->execute();

            
        }

        $statement = $pdo->prepare("CREATE TABLE IF NOT EXISTS likes (
            id int NOT NULL AUTO_INCREMENT,
            post_id int,
            user_id int,
            user_like int,
            PRIMARY KEY (id)
        );");
        $statement->execute();

        $statement = $pdo->prepare("CREATE TABLE IF NOT EXISTS comments (
            id int NOT NULL AUTO_INCREMENT,
            post_id int,
            user_id int,
            comment longtext, 
            PRIMARY KEY (id)
        );");
        $statement->execute();
        
    } catch (PDOException $exception) {
        echo 'Database error: ' . $exception->getMessage();
    }

?>