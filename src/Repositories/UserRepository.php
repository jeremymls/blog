<?php

namespace Application\Repositories;

use Application\Lib\DatabaseConnection;
use Application\Models\UserModel;

class UserRepository
{
    public DatabaseConnection $connection;

    public function addUser($username = NULL, string $password, string $email, string $first, string $last): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO users(username, password, email, first, last, role) VALUES(?, ?, ?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$username, $password, $email, $first, $last, 'user']);
        return $affectedLines > 0;
    }

    public function getUser(int $id): UserModel
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM users WHERE id = ?'
        );
        $statement->execute([$id]);
        $row = $statement->fetch();

        $user = new UserModel();
        $user->username = $row['username']?$row['username'] : "";
        $user->password = $row['password'];
        $user->email = $row['email'];
        $user->first = $row['first'];
        $user->last = $row['last'];
        $user->role = $row['role'];
        $user->id = $row['id'];
        $user->validated_email = $row['validated_email'];

        return $user;
    }

    public function updateUser(int $id, string $username = NULL, string $password, string $email, string $first, string $last): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE users SET username = ?, password = ?, email = ?, first = ?, last = ? WHERE id = ?'
        );
        $affectedLines = $statement->execute([$username, $password, $email, $first, $last, $id]);
        return $affectedLines > 0;
    }

    public function getUserByUsername($user): UserModel
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM users WHERE username = ? OR email = ?'
        );
        $statement->execute([$user, $user]);
        $row = $statement->fetch();
        if ($row === false) {
            throw new \Exception("Impossible de trouver l'utilisateur !\n Vérifiez votre identifiant ou votre mot de passe.");
        }
        $user = new UserModel();
        $user->username = $row['username']?$row['username'] : "";
        $user->password = $row['password'];
        $user->email = $row['email'];
        $user->first = $row['first'];
        $user->last = $row['last'];
        $user->role = $row['role'];
        $user->id = intval($row['id']);
        $user->validated_email = $row['validated_email'];

        return $user;
    }
}