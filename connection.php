<?php

class Connection
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=devlab_back;host=127.0.0.1', 'root', '');
    }

    public function insertUser(User $user): bool
    {
        $query = 'INSERT INTO users (first_name, last_name, pseudo, email, password) VALUES (:firstname, :lastname, :pseudo, :email, :password)';
        $statement = $this->pdo->prepare($query);

        return $statement->execute([
            'firstname' => $user->firstName,
            'lastname' => $user->lastName,
            'pseudo' => $user->pseudo,
            'email' => $user->email,
            'password' => md5($user->password . 'SALT')
        ]);
    }

    public function login($email) {
        $requete = $this->pdo->prepare('SELECT * FROM users WHERE email=?');
        $requete->execute(array($email));
        $data = $requete->fetch();
        return $data;
    }
}