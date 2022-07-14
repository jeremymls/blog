<?php

namespace Application\Repositories;

use Application\Lib\DatabaseConnection;
use Application\Models\CommentModel;

class CommentRepository
{
    public DatabaseConnection $connection;

    public function getCommentsByPost(string $post): array
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, post_id FROM comments WHERE post_id = ? and moderate = 1 ORDER BY comment_date DESC"
        );
        $statement->execute([$post]);

        $comments = [];
        while (($row = $statement->fetch())) {
            $comment = new CommentModel();
            $comment->identifier = $row['id'];
            $comment->author = $row['author'];
            $comment->frenchCreationDate = $row['french_creation_date'];
            $comment->comment = $row['comment'];
            $comment->post = $row['post_id'];

            $comments[] = $comment;
        }

        return $comments;
    }

    public function getComments($filter): array
    {
        $sql = 
        "SELECT comments.id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, post_id, posts.title, moderate 
        FROM comments 
        left join posts 
        on comments.post_id = posts.id";
        if ($filter === "unmoderated") {
            $sql .= " WHERE moderate = 0";
        } elseif ($filter === "moderated") {
            $sql .= " WHERE moderate = 1";
        } elseif ($filter === "all") {
            $sql .= "";
        }
        $sql .= " ORDER BY comment_date DESC";
        $statement = $this->connection->getConnection()->prepare($sql);
        $statement->execute();

        $comments = [];
        while (($row = $statement->fetch())) {
            $comment = new CommentModel();
            $comment->identifier = $row['id'];
            $comment->author = $row['author'];
            $comment->frenchCreationDate = $row['french_creation_date'];
            $comment->comment = $row['comment'];
            $comment->postId = $row['post_id'];
            $comment->postTitle = $row['title'];
            $comment->moderate = $row['moderate'];
            $comments[] = $comment;
        }

        return $comments;
    }

    public function getUnmoderatedComments(): array
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT p.title AS post_title, c.id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, post_id FROM comments c left join posts p on c.post_id = p.id WHERE moderate = 0 ORDER BY comment_date ASC"
        );
        $statement->execute([]);

        $comments = [];
        while (($row = $statement->fetch())) {
            $comment = new CommentModel();
            $comment->identifier = $row['id'];
            $comment->author = $row['author'];
            $comment->frenchCreationDate = $row['french_creation_date'];
            $comment->comment = $row['comment'];
            $comment->post = $row['post_id'];
            $comment->postTitle = $row['post_title'];

            $comments[] = $comment;
        }
        return $comments;
    }

    public function getUnmoderatedCommentsByProject(string $post): array
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT p.title AS post_title, c.id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, post_id FROM comments c left join posts p on c.post_id = p.id WHERE moderate = 0 and post_id=? ORDER BY comment_date DESC"
        );
        $statement->execute([$post]);

        $comments = [];
        while (($row = $statement->fetch())) {
            $comment = new CommentModel();
            $comment->identifier = $row['id'];
            $comment->author = $row['author'];
            $comment->frenchCreationDate = $row['french_creation_date'];
            $comment->comment = $row['comment'];
            $comment->post = $row['post_id'];
            $comment->postTitle = $row['post_title'];

            $comments[] = $comment;
        }
        return $comments;
    }

    public function getComment(string $identifier): ?CommentModel
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT p.title AS post_title, c.id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%i') AS french_creation_date, post_id FROM comments c left join posts p on c.post_id = p.id  WHERE c.id = ?"
        );
        $statement->execute([$identifier]);

        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        $comment = new CommentModel();
        $comment->identifier = $row['id'];
        $comment->author = $row['author'];
        $comment->frenchCreationDate = $row['french_creation_date'];
        $comment->comment = $row['comment'];
        $comment->post = $row['post_id'];
        $comment->postTitle = $row['post_title'];

        return $comment;
    }

    public function addComment(string $post, string $comment): bool
    {
        $author = $_SESSION['user']->username != "" ? ($_SESSION['user']->username) : (($_SESSION['user']->first) . " " . ($_SESSION['user']->last));
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())'
        );
        $affectedLines = $statement->execute([$post, $author, $comment]);

        return ($affectedLines > 0);
    }

    public function validateComment(string $identifier): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE comments SET moderate = 1 WHERE id = ?'
        );
        $affectedLines = $statement->execute([$identifier]);

        return ($affectedLines > 0);
    }

    public function deleteComment(string $identifier): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'DELETE FROM comments WHERE id = ?'
        );
        $affectedLines = $statement->execute([$identifier]);

        return ($affectedLines > 0);
    }

    public function updateComment(string $identifier, string $author, string $comment): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE comments SET author = ?, comment = ? WHERE id = ?'
        );
        $affectedLines = $statement->execute([$author, $comment, $identifier]);

        return ($affectedLines > 0);
    }
}
