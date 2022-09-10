<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\Comment;

class CommentRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }

    public function getCommentsByPost(string $post): array
    {
        $comments = $this->findAll("WHERE post = ? and (moderate = 1 or author = 1)", [$post]);
        foreach ($comments as $comment) {
            $comment->with('post', PostRepository::class);
            $comment->with('author', UserRepository::class);
        }
        return $comments;
    }

    public function getCommentsByUser(string $user): array
    {
        $comments = $this->findAll("WHERE author = ?", [$user]);
        foreach ($comments as $comment) {
            $comment->with('post', PostRepository::class);
        }
        return $comments;
    }

    public function getCommentsForBo($filter)
    {
        $option = "";
        switch ($filter) {
            case "all":
                $option = "";
                break;
            case "pending":
                $option = " WHERE moderate = 0";
                break;
            case "approved":
                $option = " WHERE moderate = 1";
                break;
            case "rejected":
                $option = " WHERE moderate = 2";
                break;
        }
        $comments = $this->findAll($option);
        foreach ($comments as $comment) {
            $comment->with('post', PostRepository::class);
            $comment->with('author', UserRepository::class);
        }
        return $comments;
    }

    public function getComment(string $identifier)
    {
        $comment= $this->findOne($identifier);
            $comment->with('post', PostRepository::class);
            $comment->with('author', UserRepository::class);
        return $comment;
    }

    public function addComment(string $post, string $comment): bool
    {
        $author = $_SESSION['user']->id;
        $statement = $this->connection::$database->prepare('INSERT INTO comments(post, author, comment, created_at) VALUES(?, ?, ?, NOW())');
        $affectedLines = $statement->execute([$post, $author, $comment]);
        return ($affectedLines > 0);
    }

    public function moderate(string $action, string $identifier): bool
    {
        $statement = $this->connection::$database->prepare("UPDATE comments SET moderate = $action WHERE id = ?");
        $affectedLines = $statement->execute([$identifier]);
        return ($affectedLines > 0);
    }
}
