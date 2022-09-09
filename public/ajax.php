<?php

use app\student;

require_once __DIR__ . '/../controller/autoload.controller.php';
$stm = new student\model\Database();
$stc = new student\controller\Index();
$stv = new student\view\Msg();

$action = @$_POST['action'];
if ($action === 'delete') {
    echo $stc->del(@$_POST['id']);
}

if ($action === 'delMul') {
    echo $stc->delMul(@$_POST['data']);
}

if ($action === 'add') {
    echo $stc->add(@$_POST['name'], @$_POST['sex'], @$_POST['tel'], @$_POST['room'], @$_POST['major']);
}

if ($action === 'selectAll') {
    $data = $stm->select(type: $_POST['type'], orderType: @$_POST['order']);
    try {
        echo json_encode($data, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
    }
}

if ($action === 'selectLastOne') {
    $data = $stm->select(orderType: 'desc');
    try {
        echo json_encode($data, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
    }
}

if ($action === 'selectIdOne') {
    try {
        echo json_encode($stm->select(conditions: "id={$_POST['id']}"), JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
    }
//  echo json_encode($stm->selectIdOne(@$_POST['id']));
}

if ($action === 'alter') {
    echo $stc->alter(@$_POST['id'], @$_POST['name'], @$_POST['sex'], @$_POST['tel'], @$_POST['room'], @$_POST['major']);
}

if ($action === 'login') {
    echo $stc->login(@$_POST['user'], @$_POST['pass']);
}

if ($action === 'logout') {
    echo $stc->logout();
}