<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware;

// todo : move this to services
/**
 * Pagination
 *
 * Service to paginate the entities
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Pagination
{
    /**
     * Paginate
     *
     * Paginate the entities
     *
     * @param mixed $params   The parameters to pass to the view
     * @param mixed $entity   The name of the entity to paginate
     * @param mixed $nbr_show The number of entities to show per page
     *
     * @return array The parameters to pass to the view
     */
    public function paginate($params, $entity, $nbr_show = 3)
    {
        if ($nbr_show > 0) {
            $superglobals = Superglobals::getInstance();
            $page = $superglobals->getGet('page') ?? 1;
            $nbPage = ceil(count($params[$entity]) / $nbr_show);
            $params[$entity] = array_slice(
                $params[$entity],
                ($page - 1) * $nbr_show,
                $nbr_show
            );
            if ($params[$entity] == [] && $page > 1) {
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
