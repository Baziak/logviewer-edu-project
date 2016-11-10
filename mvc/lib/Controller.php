<?php

class Controller
{
    protected $actionsDirectory = '';
    protected $modelsDirectory = '';
    protected $viewsDirectory = '';
    protected $defaultAction = '';
    protected $logsDirectory = '';

    protected $additionalOptions = [];

    public function __construct(array $options = [])
    {
        foreach ($options as $name => $value) {
            if (isset($this->$name)) {
                $this->$name = $value;
                unset($options[$name]);
            }
        }

        $this->additionalOptions = $options;
    }

    public function run()
    {
        $action = $this->doRouting();
        $this->dispatch($action);
    }

    protected function doRouting() : string
    {
        $action = isset($_GET['action'])
            ? $_GET['action']
            : $this->defaultAction;

        return preg_replace('/[^a-zA-Z0-9\-]/', '', $action);
    }

    protected function dispatch($action)
    {
        $data = $this->runActionFile($action);
        $this->renderView($action, $data);
    }

    protected function runActionFile($action)
    {
        $data = include $this->actionsDirectory
            . DIRECTORY_SEPARATOR . $action . '.php';

        return $data;
    }

    protected function renderView($action, $data)
    {
        if (is_array($data)) {
            extract($data);

            ob_start();
            include $this->viewsDirectory
                . DIRECTORY_SEPARATOR . $action . '.phtml';
            $content = ob_get_clean();

            include $this->viewsDirectory . DIRECTORY_SEPARATOR . 'layout.phtml';
        }
    }
}