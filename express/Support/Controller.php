<?php

namespace Fyyb\Support;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Controller
{

    /**
     * @var string
     */
    protected $dir_section = '';

    /**
     * @var string
     */
    protected $dir_page = '';

    /**
     * @var string
     */
    protected $dir_template = '';

    /**
     * @param String $viewName
     * @param Array $viewData
     * @return void
     */
    protected function loadPage(String $viewName, array $viewData = array())
    {
        extract($viewData);
        if (file_exists($this->dir_page . DIRECTORY_SEPARATOR . $viewName . '.php')) {
            require $this->dir_page . DIRECTORY_SEPARATOR . $viewName . '.php';
        };

        return false;
    }

    /**
     * @param String $viewName
     * @param String $template
     * @param Array $viewData
     * @return void
     */
    protected function loadViewInTemplate(String $viewName, String $template, array $viewData = array())
    {
        if (file_exists($this->dir_template . DIRECTORY_SEPARATOR . $template . '.php')) {
            require $this->dir_template . DIRECTORY_SEPARATOR . $template . '.php';
        };

        return false;
    }

    /**
     * @param String $sectionName
     * @param Array $sectionData
     * @return void
     */
    protected function loadSection(String $sectionName, array $sectionData = array())
    {
        extract($sectionData);
        if (file_exists($this->dir_section . DIRECTORY_SEPARATOR . $sectionName . '.php')) {
            require $this->dir_section . DIRECTORY_SEPARATOR . $sectionName . '.php';
        };

        return false;
    }
}