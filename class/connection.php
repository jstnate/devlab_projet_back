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

    public function getUsers()
    {
        $query = 'SELECT * FROM users';
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * From users WHERE email = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($email));
        return $statement->fetch();
    }

    public function getUserByName($name)
    {
        $query = 'SELECT * FROM users WHERE pseudo LIKE ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array('%' . $name . '%'));
        return $statement->fetchAll();
    }

    public function getUserById($id)
    {
        $query = 'SELECT * FROM users WHERE id LIKE ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($id));
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

    public function getAllAlbums($id)
    {
        $query = 'SELECT * FROM albums JOIN albums_autorisations ON album_id = albums.id WHERE user_authorized = ?';
        $statement = $this->pdo->prepare($query);
        $statement ->execute(array($id));
        return $statement->fetchAll();
    }

    public function getPublicAlbums($id)
    {
        $query = 'SELECT * FROM albums JOIN albums_autorisations ON album_id = albums.id WHERE user_authorized = ? AND privacy = "0"';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($id));
        return $statement->fetchAll();
    }

    public function getLikedAlbums($id)
    {
        $query = 'SELECT * FROM albums JOIN albums_likes ON album_id = albums.id WHERE user_liking = ?';
        $statement = $this->pdo->prepare($query);
        $statement ->execute(array($id));
        return $statement->fetchAll();
    }

    public function albumLike($album, $user)
    {
        $query = 'INSERT INTO albums_likes (album_id, user_liking) VALUES (:albumId, :userId)';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'albumId' => $album,
            'userId' => $user
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

    public function removeFilm($film, $album): bool
    {
        $query = 'DELETE FROM movies_albums WHERE movie_id = :film AND album_id = :album';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'film' => $film,
            'album' => $album
        ]);
    }

    public function sendMessage($shipperName, $albumId, $albumName, $receiverId)
    {
        $query = 'INSERT INTO share_message (shipper_name, album_id, album_name, receiver_id) VALUES (:shipper, :albumId, :albumName, :receiver)';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'shipper' => $shipperName,
            'albumId' => $albumId,
            'albumName' => $albumName,
            'receiver' => $receiverId
        ]);
    }

    public function getMessage($receiverId)
    {
        $query = 'SELECT * FROM share_message WHERE receiver_id = ?';
        $statement = $this->pdo->prepare($query);
        $statement->execute(array($receiverId));
        return $statement->fetchAll();
    }

    public function addAutorisation($album, $user)
    {
        $query = 'INSERT INTO albums_autorisations (album_id, user_authorized) VALUES (:album, :user)';
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'album' => $album,
            'user' => $user
        ]);
    }

    public function deleteMessage($message)
    {
        $query = 'DELETE FROM share_message WHERE id = ?';
        $statement = $this->pdo->prepare($query);
        return $statement->execute(array($message));
    }
}