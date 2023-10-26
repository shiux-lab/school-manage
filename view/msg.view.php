<?php

namespace app\student\view;
class Msg
{
    public function displayDelMsg($result): string
    {
        return $result ? '删除成功' : '删除失败';
    }

    public function displayAddMsg($result): string
    {
        if ($result) {
            return '添加成功';
        }

        header('HTTP/1.1 502 Bad Gateway');
        return '添加失败';
    }

    public function displayLoginMsg($result): string
    {
        if ($result) {
            return '登录成功';
        }

        header('HTTP/1.1 502 Bad Gateway');
        return '登录失败,请重新登录';
    }

    public function displayLogoutMsg($result): string
    {
        if ($result) {
            return '注销成功';
        }

        header('HTTP/1.1 502 Bad Gateway');
        return '注销失败';
    }

    public function displayAlterMsg($result): string
    {
        if ($result) {
            return '修改成功';
        }

        header('HTTP/1.1 502 Bad Gateway');
        return '修改失败';
    }
}
