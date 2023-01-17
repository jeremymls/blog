<?php
namespace Core\Middleware;

/**
 * TemplateRenderer
 * 
 * Render a template and save it in CSS file
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
     * @param  mixed $templating The templating service (twig)
     * @param  mixed $basepath "%kerel.base_path" parameter
     * @param  mixed $stylesheetpath Custom defined parameter
     * @return void
     */
    public function __construct($templating, $basepath, $stylesheetpath)
    {
        $this->basepath = $basepath;
        $this->templating = $templating;
        $this->stylesheetpath = $stylesheetpath;
    }

    /**
     * setParam
     * 
     * Set a parameter to be used in the template
     *
     * @param  mixed $id The key of the parameter
     * @param  mixed $value The value of the parameter
     */
    public function setParam($id, $value)
    {
        $this->parameters[$id] = $value;
    }

    /**
     * render
     * 
     * Render the template and save it in CSS file
     */
    public function render()
    {
        file_put_contents(
            $this->basepath.'/assets/css/styles.css',
            $this->templating->render(
                $this->stylesheetpath,
                $this->parameters
            )
        );
    }
}