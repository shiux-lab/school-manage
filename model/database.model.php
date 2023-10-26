<?php

namespace app\student\model;

use PDO;

class Database
{
    private string $db_drive = DRIVE; // 数据库驱动
    private string $charset = DB_CHARSET; // 数据库字符集
    private string $table = TABLE; // 数据表
    private string $admin_table = ADMIN_TABLE;
    public ?PDO $conn = null; // 连接
//  MySQL
    private string $db_host = HOST;
    private string $db_port = PORT;
    private string $db_name = DBNAME;

    private string $db_user = USER;
    private string $db_pass = PASS;

//  SQLite
    private string $sqlite_file = FILE;

//  初始化变量
    public function __construct()
    {
        return $this->conn = $this->db_drive == 'sqlite' ?
            new PDO("$this->db_drive:$this->sqlite_file")
            : new PDO("$this->db_drive:host=$this->db_host:$this->db_port;dbname=$this->db_name;charset=$this->charset", $this->db_user, $this->db_pass);
    }

//  查询所有记录
    public function select(string $conditions = null, string $type = 'id', string $orderType = 'asc', int $start = 0, int $num = null): array
    {
        $sql = "select * from {$this->table}";
        $sql .= ($conditions ? " where {$conditions}" : '');
        $sql .= " order by {$type} {$orderType}";
        $sql .= $num !== null ? ($start ? " limit {$start}, {$num}" : "limit {$num}") : '';
        $data = $this->conn->query($sql)->fetchAll();
        if (count($data) === 1) {
            return $data[0];
        }
        return $data;
    }

//  删除一条记录
    public function delOne($id): bool|\PDOStatement
    {
        return $this->conn->exec("delete from {$this->table} where id={$id}");
    }

//  删除多条记录
    public function delMul($id): bool|\PDOStatement
    {
        return $this->conn->exec("delete from {$this->table} where id in({$id})");
    }

//  添加一条记录
    public function addOne($name, $sex, $tel, $room, $major): bool|\PDOStatement
    {
        $sql = $this->conn->prepare("insert into {$this->table} values(null, ?, ?, ?, ?, ?)");
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $sex);
        $sql->bindValue(3, $tel);
        $sql->bindValue(4, $room);
        $sql->bindValue(5, $major);
        return $sql->execute();
    }

//  修改一条记录
    public function alterOne($id, $name, $sex, $tel, $room, $major): bool|\PDOStatement
    {
        $sql = $this->conn->prepare("update {$this->table} set name=?,sex=?,tel=?,room=?,major=? where id=?");
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $sex);
        $sql->bindValue(3, $tel);
        $sql->bindValue(4, $room);
        $sql->bindValue(5, $major);
        $sql->bindValue(5, $id);
        return $sql->execute();
    }

//  登录判断
    public function checkLogin($user, $pwd): bool
    {
        return $this->conn->query("select count(*) from {$this->admin_table} where user='{$user}' and pwd='{$pwd}'")->fetch(PDO::FETCH_NUM)[0] && $this->conn->query("update {$this->admin_table} set status=1 where user='{$user}'");
    }

//  退出登录
    public function logout(): bool|\PDOStatement
    {
        session_start();
        return $this->conn->exec("update {$this->admin_table} set status=0 where user='{$_SESSION['admin'][0]}'");
    }
}