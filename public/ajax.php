<?php

use app\student;

require_once __DIR__ . '/../controller/autoload.controller.php';
$stm = new student\model\Database();
$stc = new student\controller\Index();
$stv = new student\view\Msg();

$action = @$_POST['action'];
if ($action == 'delete') {
  echo $stc->del(@$_POST['id']);
}

if ($action == 'delMul') {
  echo $stc->delMul(@$_POST['data']);
}

if ($action == 'add') {
  echo $stc->add(@$_POST['name'], @$_POST['sex'], @$_POST['tel'], @$_POST['room'], @$_POST['major']);
}

if ($action == 'selectAll') {
  $data = $stm->select(type: $_POST['type'], order: @$_POST['order']);
  echo json_encode($data);
}

if ($action == 'selectLastOne') {
  $data = $stm->select(order: 'desc');
  echo json_encode($data);
}

if ($action == 'selectIdOne') {
  echo json_encode($stm->select(conditions: "id={$_POST['id']}"));
//  echo json_encode($stm->selectIdOne(@$_POST['id']));
}

if ($action == 'alter') {
  echo $stc->alter(@$_POST['id'], @$_POST['name'], @$_POST['sex'], @$_POST['tel'], @$_POST['room'], @$_POST['major']);
}

if ($action == 'login') {
  echo $stc->login(@$_POST['user'], @$_POST['pass']);
}

if ($action == 'logout') {
  echo $stc->logout();
}