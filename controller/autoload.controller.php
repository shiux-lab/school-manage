<?php
class autoloader
{

  public static $loader;

  public static function init()
  {
    if (self::$loader == NULL)
      self::$loader = new self();

    return self::$loader;
  }

  public function __construct()
  {
    require_once __DIR__ . '/../config/database.php';
    spl_autoload_register(array($this, 'model'));
    spl_autoload_register(array($this, 'controller'));
    spl_autoload_register(array($this, 'view'));
  }

  public function view($class)
  {
    $class = strtolower(basename(str_replace('\\','/', $class)));
    set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../view/');
    spl_autoload_extensions('.view.php');
    spl_autoload($class);
  }

  public function controller($class)
  {
    $class = strtolower(basename(str_replace('\\','/', $class)));
    set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../controller/');
    spl_autoload_extensions('.controller.php');
    spl_autoload($class);
  }

  public function model($class)
  {
    $class = strtolower(basename(str_replace('\\','/', $class)));
    set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../model/');
    spl_autoload_extensions('.model.php');
    spl_autoload($class);
  }
}

autoloader::init();