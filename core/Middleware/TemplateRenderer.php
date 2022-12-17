<?php
namespace Core\Middleware;

class TemplateRenderer
{
    protected $basepath;
    protected $templating;
    protected $parameters = array();
    protected $stylesheetpath;

    public function __construct($templating, $basepath, $stylesheetpath)
    {
        $this->basepath = $basepath; /* "%kerel.base_path" parameter */
        $this->templating = $templating; /* "twig" service */
        $this->stylesheetpath = $stylesheetpath; /* custom defined parameter */
    }

    public function setParam($id, $value)
    {
        $this->parameters[$id] = $value;
    }

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