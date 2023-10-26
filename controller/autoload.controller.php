<?php

class autoloader
{
    public static function init(): autoloader
    {
        return new self();
    }

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        spl_autoload_register(array($this, 'model'));
//        spl_autoload_register(array($this, 'helper'));
        spl_autoload_register(array($this, 'controller'));
//        spl_autoload_register(array($this, 'library'));
        spl_autoload_register(array($this, 'view'));
    }

//    public function library($class): void
//    {
//        set_include_path(get_include_path() . PATH_SEPARATOR . '/lib/');
//        spl_autoload_extensions('.library.php');
//        spl_autoload($class);
//    }

    public function view($class): void
    {
        $class = strtolower(basename(str_replace('\\', '/', $class)));
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../view/');
        spl_autoload_extensions('.view.php');
        spl_autoload($class);
    }

    public function controller($class): void
    {
        $class = strtolower(basename(str_replace('\\', '/', $class)));
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../controller/');
        spl_autoload_extensions('.controller.php');
        spl_autoload($class);
    }

    public function model($class): void
    {
        $class = strtolower(basename(str_replace('\\', '/', $class)));
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../model/');
        spl_autoload_extensions('.model.php');
        spl_autoload($class);
    }

//    public function helper($class): void
//    {
//        $class = preg_replace('/_helper$/ui', '', $class);
//
//        set_include_path(get_include_path() . PATH_SEPARATOR . '/helper/');
//        spl_autoload_extensions('.helper.php');
//        spl_autoload($class);
//    }

}

//call
autoloader::init();