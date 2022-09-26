<?php

namespace Core\Services;

use Application\Repositories\CommentRepository;
use Application\Repositories\PostRepository;
use Application\Repositories\UserRepository;
use Core\Repositories\TokenRepository;
use Core\Middleware\Pagination;

class Service
{
    public function __construct()
    {
        $this->flashServices = new FlashService();
        $this->pagination = new Pagination();
        $this->postRepository = new PostRepository();
        $this->commentRepository = new CommentRepository();
        $this->userRepository = new UserRepository();
        $this->tokenRepository = new TokenRepository();
    }

    public function getRepository()
    {
        $class = get_class($this);
        $class = str_replace("Services", "Repositories", $class);
        $class = str_replace("Service", "Repository", $class);
        return new $class();
    }

    public function getModel()
    {
        $class = get_class($this);
        $class = str_replace("Services", "Models", $class);
        $class = str_replace("Service", "", $class);
        return new $class();
    }

    public function getModelName() : string
    {
        $str = get_class($this->getModel());
        $str = strrchr($str, '\\');
        $str = substr($str, 1);
        $str = strtolower($str);
        return $str;
    }

    /**
     * Return the french name of the model, with the correct article and plural form
     * 
     * @param uppercase if true, the first letter of the string will be uppercased
     * @param articleType D, I or N (Definite, Indefinite or Null)
     * @param number S for singular, P for plural
     * @param gender M for male, F for female
     * 
     * @return string The french name of the model
     */
    public function getFrenchName($uppercase = false, $articleType = "D", $number = "S") : string
    {
        require_once 'src/Config/translations.php';
        if (isset(FR_HELPER[$this->getModelName()][0])){
            $modelFrenchName = FR_HELPER[$this->getModelName()][0];
        } else {
            throw new \Exception("No french name found for " . $this->getModelName() . " <br> Please add it to src/Config/translations.php");
        }
        $gender = FR_HELPER[$this->getModelName()][1];
        $str = "";
        if ($articleType === "D") {
            if ($number == "S") {
                if (in_array(substr($modelFrenchName, 0, 1), VOYELLES) && substr($modelFrenchName, 0, 2) !== "ha") {
                    $str .= "l'";
                } elseif ($gender == "M") {
                    $str .= "le ";
                } elseif ($gender == "F") {
                    $str .= "la ";
                }
            } elseif ($number == "P") {
                $str .= "les ";
            } else {
                throw new \Exception("Le nombre doit être S ou P (Singulier ou Pluriel)");
            }
        } elseif ($articleType === "I") {
            # code...
        } elseif ($articleType === "N") {
        } else {
            throw new \Exception("Le type d'article doit être D, I ou N (Défini, Indéfini ou Nul)");
        }
        $str .= $modelFrenchName;
        if ($number == "P") {
            $str .= "s";
        }
        if ($uppercase) {
            $str = ucfirst($str);
        }

        return $str;
    }

    public function getFrenchGenderTermination() : string
    {
        require_once 'src/Config/translations.php';
        if (isset(FR_HELPER[$this->getModelName()][0])){
            $gender = FR_HELPER[$this->getModelName()][1];
        } else {
            throw new \Exception("No french name found for " . $this->getModelName() . " <br> Please add it to src/Config/translations.php");
        }
        $str = $gender == "M" ? "" : "e";
        return $str;
    }

    public function validateForm(array $input, array $requiredFields = [])
    {
        $conditions = [];
        foreach ($requiredFields as $key => $field) {
            $conditions[] = !empty($input[$field])?'ok':'ko';
        }
        if (!in_array("ko", $conditions) ) {
            foreach ($input as $key => $value) {
                $this->model->$key = $value;
            } 
            return $this->model;
        }else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }
    }
}
