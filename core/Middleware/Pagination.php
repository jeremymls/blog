<?php

namespace Core\Middleware;

class Pagination
{
    public function paginate($params, $entities, $nbp = 3)
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $nbPage = ceil(count($params[$entities]) / $nbp);
        $params[$entities] = array_slice($params[$entities], ($page - 1) * $nbp, $nbp);
        if ($params[$entities] == [] && $page > 1) {
            header('Location: ' . Superglobals::getInstance()->getPathWithoutGet());
        }
        $params['nbPage'] = $nbPage;
        return $params;
    }
}
