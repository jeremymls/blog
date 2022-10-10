<?php

namespace Core\Services;

use Core\Middleware\Pagination;

class Service
{
    public function __construct()
    {
        $this->flashServices = new FlashService();
        $this->pagination = new Pagination();
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
     * ***
     * __OPTIONS__
     * * $uppercase : if __true__ return the name with the first letter in uppercase
     * * $articleType : Type of article to use
     * >* "D" for definite article _(default)_
     * >* "I" for indefinite article
     * >* "N" for no article
     * * $plural : if __true__ return the plural form of the name
     * ***
     * 
     * @param bool $uppercase Return the name with the first letter in uppercase
     * @param string $articleType Type of article to use
     * @param bool $plural Return the plural form of the name
     * 
     * @return string The french name of the model
     */
    public function getFrenchName($uppercase = false, $articleType = "D", $plural = false) : string
    {
        require_once 'src/config/translations.php';
        if (isset(FR_HELPER[$this->getModelName()][0])){
            $modelFrenchName = FR_HELPER[$this->getModelName()][0];
        } else {
            throw new \Exception("No french name found for " . $this->getModelName() . " <br> Please add it to src/Config/translations.php");
        }
        $gender = FR_HELPER[$this->getModelName()][1];
        $str = "";
        if ($articleType === "D") {
            if ($plural) {
                $str .= "les ";
            } else {
                if (in_array(substr($modelFrenchName, 0, 1), VOYELLES) && substr($modelFrenchName, 0, 2) !== "ha") {
                    $str .= "l'";
                } elseif ($gender == "M") {
                    $str .= "le ";
                } elseif ($gender == "F") {
                    $str .= "la ";
                }
            }
        } elseif ($articleType === "I") {
            # code...
        } elseif ($articleType === "N") {
        } else {
            throw new \Exception("Le type d'article doit être D, I ou N (Défini, Indéfini ou Nul)");
        }
        $str .= $modelFrenchName;
        if ($plural) { $str .= "s"; }
        if ($uppercase) {
            $str = ucfirst($str);
        }

        return $str;
    }

    public function getFrenchGenderTermination() : string
    {
        require_once 'src/config/translations.php';
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
