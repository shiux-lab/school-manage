<?php

namespace app\student\controller;

use app\student;

class Index
{
    public student\model\Database $stm;
    public student\view\Msg $stv;

    public function __construct()
    {
        $this->stm = new student\model\Database();
        $this->stv = new student\view\Msg();
    }

    public function add($name, $sex, $tel, $room, $major): string
    {
        return $this->stv->displayAddMsg($this->stm->addOne($name, $sex, $tel, $room, $major));
    }

    public function del($id): string
    {
        return $this->stv->displayDelMsg($this->stm->delOne($id));
    }

    public function alter($id, $name, $sex, $tel, $room, $major): string
    {
        return $this->stv->displayAlterMsg($this->stm->alterOne($id, $name, $sex, $tel, $room, $major));
    }

    public function delMul($id): string
    {
        return $this->stv->displayDelMsg($this->stm->delMul($id));
    }

    public function login($user, $pass): string
    {
        if ($check = $this->stm->checkLogin($user, md5($pass))) {
            session_start();
            $_SESSION['admin'] = [$user, '1'];
        }
        return $this->stv->displayLoginMsg($check);
    }

    public function logout(): string
    {

        if ($check = $this->stm->logout())
            unset($_SESSION['admin']);
        return $this->stv->displayLogoutMsg($check);
    }
}
