<?php

namespace Core\Middleware;

class Pagination
{
    public function paginate($params, $entities, $nbr_show = 3)
    {
        if ($nbr_show > 0) {
            $superglobals = Superglobals::getInstance();
            $page = $superglobals->getGet('page') ?? 1;
            $nbPage = ceil(count($params[$entities]) / $nbr_show);
            $params[$entities] = array_slice($params[$entities], ($page - 1) * $nbr_show, $nbr_show);
            if ($params[$entities] == [] && $page > 1) {
                header('Location: ' . $superglobals->getPathWithoutGet());
            }
            $params['nbPage'] = $nbPage;
        } else {
            $params['nbPage'] = 1;
        }
        $params['nbr_show'] = $nbr_show;
        return $params;
    }
}
