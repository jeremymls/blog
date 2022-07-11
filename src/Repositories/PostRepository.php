<?php

namespace Application\Repositories;

use Application\Lib\DatabaseConnection;
use Application\Models\PostModel;

class PostRepository
{
    public DatabaseConnection $connection;

    public function getPost(string $identifier): PostModel
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT id, title, content, DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, url, chapo FROM posts WHERE id = ?"
        );
        $statement->execute([$identifier]);

        $row = $statement->fetch();
        $post = new PostModel();
        $post->title = $row['title'];
        $post->frenchCreationDate = $row['french_creation_date'];
        $post->chapo = $row['chapo'] != null ? $row['chapo'] : '';
        $post->content = $row['content'];
        $post->identifier = $row['id'];
        $post->url = $row['url'] != null ? $row['url'] : '';

        return $post;
    }

    public function getPosts(): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT id, title, content, DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, url, chapo FROM posts ORDER BY creation_date DESC LIMIT 0, 5"
        );
        $posts = [];
        while (($row = $statement->fetch())) {
            $post = new PostModel();
            $post->title = $row['title'];
            $post->frenchCreationDate = $row['french_creation_date'];
            $post->chapo = $row['chapo'] != null ? $row['chapo'] : '';
            $post->content = $row['content'];
            $post->identifier = $row['id'];
            $post->url = $row['url'] != null ? $row['url'] : '';

            $posts[] = $post;
        }

        return $posts;
    }

    public function add(string $title, string $content, string $url, string $chapo): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO posts(title, content, url, chapo, creation_date) VALUES(?, ?, ?, ?, NOW())'
        );
        $affectedLines = $statement->execute([$title, $content, $url, $chapo]);

        return ($affectedLines > 0);
    }

    public function update(string $identifier, string $title, string $content, string $url, string $chapo): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE posts SET title = ?, content = ?, url = ?, chapo = ? WHERE id = ?'
        );
        $affectedLines = $statement->execute([$title, $content, $url, $chapo, $identifier]);

        return ($affectedLines > 0);
    }

    public function delete(string $identifier): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'DELETE FROM posts WHERE id=?'
        );
        $affectedLines = $statement->execute([$identifier]);

        return ($affectedLines > 0);
    }


}
