<?php
    
    function get_user_by_email($email) {
        require __DIR__.'/../seed.php';
        
        $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email;");
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll();
        
        if (count($result) == 1) {
            return $result[0];
        } else {
            return null;
        }
        
    }

    function correct_password_for_user($user, $password) {
        return ($user != null && md5($password) == $user['password_hash']);
    }

    function createUser($name, $surname, $email, $avatar_path, $password_hash, $admin) {
        require __DIR__.'/../seed.php';
        
        $sql = "INSERT INTO users (name, surname, email, avatar_path, password_hash, admin) 
        VALUES (:name, :surname, :email, :avatar_path, :password_hash, :admin);";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':surname', $surname, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':avatar_path', $avatar_path, PDO::PARAM_STR);
        $statement->bindValue(':password_hash', md5($password_hash), PDO::PARAM_STR);
        $statement->bindValue(':admin', $admin, PDO::PARAM_STR);
        $statement->execute();
            
        
        
    }

?>