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

/**
 * TemplateRenderer
 *
 * Render a template and save it in CSS file
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class TemplateRenderer
{
    protected $basepath;
    protected $templating;
    protected $parameters = array();
    protected $stylesheetpath;

    /**
     * Constructor
     *
     * @param mixed $templating     The templating service (twig)
     * @param mixed $basepath       "%kerel.base_path" parameter
     * @param mixed $stylesheetpath Custom defined parameter
     *
     * @return void
     */
    public function __construct($templating, $basepath, $stylesheetpath)
    {
        $this->basepath = $basepath;
        $this->templating = $templating;
        $this->stylesheetpath = $stylesheetpath;
    }

    /**
     * Set Param
     *
     * Set a parameter to be used in the template
     *
     * @param mixed $id    The key of the parameter
     * @param mixed $value The value of the parameter
     *
     * @return void
     */
    public function setParam($id, $value)
    {
        $this->parameters[$id] = $value;
    }

    /**
     * Render
     *
     * Render the template and save it in CSS file
     *
     * @return void
     */
    public function render()
    {
        file_put_contents(
            $this->basepath . '/assets/css/styles.css',
            $this->templating->render(
                $this->stylesheetpath,
                $this->parameters
            )
        );
    }
}
