<?php
namespace app\student\controller;
use app\student;
class Index
{
  public $stm;
  public $stv;

  public function __construct()
  {
    $this->stm = new student\model\Database();
    $this->stv = new student\view\Msg();
  }

  public function add($name, $sex, $tel, $room, $major)
  {
    return $this->stv->displayAddMsg($this->stm->addOne($name, $sex, $tel, $room, $major));
  }

  public function del($id)
  {
    return $this->stv->displayDelMsg($this->stm->delOne($id));
  }

  public function alter($id, $name, $sex, $tel, $room, $major)
  {
    return $this->stv->displayAlterMsg($this->stm->alterOne($id, $name, $sex, $tel, $room, $major));
  }

  public function delMul($id)
  {
    return $this->stv->displayDelMsg($this->stm->delMul($id));
  }

  public function login($user, $pass)
  {
    if ($check = $this->stm->checkLogin($user, md5($pass))) {
      session_start();
      $_SESSION['admin'] = [$user, '1'];
    }
    return $this->stv->displayLoginMsg($check);
  }

  public function logout()
  {

    if ($check = $this->stm->logout())
      unset($_SESSION['admin']);
    return $this->stv->displayLogoutMsg($check);
  }
}
