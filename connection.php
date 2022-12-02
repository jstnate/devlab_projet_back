<?php

class Connection
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=devlab_back;host=127.0.0.1', 'root', 'root');
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
}