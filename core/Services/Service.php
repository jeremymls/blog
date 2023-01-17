<?php

namespace Core\Services;

use Core\Middleware\Pagination;
use Core\Middleware\Superglobals;

/**
 * Service
 * 
 * Base service class
 */
class Service
{
    protected $flashServices;
    protected $pagination;
    protected $superglobals;
    protected $model;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->flashServices = FlashService::getInstance();
        $this->pagination = new Pagination();
        $this->superglobals = Superglobals::getInstance();
    }

    /**
     * getRepository
     * 
     * Get the repository of the service
     * 
     * @return object The repository of the service
     */
    public function getRepository()
    {
        $class = get_class($this);
        $class = str_replace("Services", "Repositories", $class);
        $class = str_replace("Service", "Repository", $class);
        return new $class();
    }

    /**
     * getModel
     * 
     * Get the model of the service
     * 
     * @return object The model of the service
     */
    public function getModel()
    {
        $class = get_class($this);
        $class = str_replace("Services", "Models", $class);
        $class = str_replace("Service", "", $class);
        return new $class();
    }

    /**
     * getModelName
     * 
     * Get the name of the model of the service
     * 
     * @return string The name of the model of the service
     */
    public function getModelName() : string
    {
        $str = get_class($this->getModel());
        $str = strrchr($str, '\\');
        $str = substr($str, 1);
        $str = strtolower($str);
        return $str;
    }

    /**
     * getFrenchName
     * 
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

    /**
     * getFrenchGenderTermination
     * 
     * Return the termination to use for the french name of the model
     * 
     * @return string The french gender termination (e or nothing)
     */
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

    /**
     * validateForm
     * 
     * Validate an entity from his model
     * 
     * @param array $input
     * @param array $requiredFields
     * @throws \Exception
     * @return mixed
     */
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
