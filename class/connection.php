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
        return $this->pdo->prepare($query)->execute([
            'firstname' => $user->firstName,
            'lastname' => $user->lastName,
            'pseudo' => $user->pseudo,
            'email' => $user->email,
            'password' => md5($user->password . 'SALT')
        ]);
    }

    public function getUserId($email)
    {
        $query = "SELECT id From users WHERE email = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($email));
        return $statement->fetch();
    }
    public function emailVerify($email)
    {
        $query = 'SELECT * FROM users WHERE email = ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($email));
        return $statement->fetch();
    }

    public function createAlbum(Album $album)
    {
        $query = 'INSERT INTO albums (name, user_id, privacy) VALUES (:albumName, :user_id, :privacy)';
        $statement = $this->pdo->prepare($query);

        return $statement->execute([
            'albumName' => $album->albumName,
            'user_id' => $_SESSION['user_id'],
            'privacy' => $album->privacy
        ]);
    }

    public function getAlbum($name)
    {
        $query = 'SELECT id FROM albums WHERE user_id = :id AND name = :name';
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            'id' => $_SESSION['user_id'],
            'name' => $name
        ]);
        return $statement->fetch();
    }

    public function getAllAlbums()
    {
        $query = 'SELECT * FROM albums WHERE user_id = ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($_SESSION['user_id']));
        return $statement;
    }

    public function insertInLiked($id)
    {
        $query = 'INSERT INTO movies_albums (movie_id, album_id) VALUES (:movie, :album)';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'movie' => $id,
            'album' => $_SESSION['liked']
        ]);
    }
    public function insertInWatched($id)
    {
        $query = 'INSERT INTO movies_albums (movie_id, album_id) VALUES (:movie, :album)';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'movie' => $id,
            'album' => $_SESSION['watched']
        ]);
    }
    public function insertFilm($film, $album): bool
    {
        $query = 'INSERT INTO movies_albums (movie_id, album_id) VALUES (:movieId, :albumId)';
        return $this->pdo->prepare($query)->execute([
            'movieId' => $film,
            'albumId' => $album
        ]);
    }

    public function getFilms($album) {
        $query = 'SELECT * FROM movies_albums WHERE album_id = ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($album));
        return $statement;
    }
}