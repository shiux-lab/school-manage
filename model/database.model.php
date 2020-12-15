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

//  MongoDB
//  private string $mongo_user = MONGO_USER;
//  private string $mongo_pass = MONGO_PASS;

//  初始化变量
  public function __construct()
  {
    return $this->conn = $this->db_drive == 'sqlite' ?
        new PDO("$this->db_drive:$this->sqlite_file")
        : new PDO("$this->db_drive:host=$this->db_host:$this->db_port;dbname=$this->db_name;charset=$this->charset", $this->db_user, $this->db_pass);
//    if ($this->db_drive == 'sqlite') {
//      new PDO("$this->db_drive:$this->sqlite_file");
//    } elseif ($this->db_drive == 'mongo') {
//      new MongoDB();
//    } else {
//      new PDO("$this->db_drive:host=$this->db_host:$this->db_port;dbname=$this->db_name;charset=$this->charset" , $this->db_user, $this->db_pass);
//    }
//    $this->conn
//    return $this->conn =  ?
//
//      :
  }

//  查询所有记录
  public function select(string $conditions = null, string $type = 'id', string $order = 'asc', int $start = 0, int $num = null): array
  {
    $sql = "select * from {$this->table}{$conditions}";
    $sql .= ($conditions ? " where {$conditions}" : '') . " order by {$type} {$order}";
    $sql .= !empty($num) ? ($start ? " limit {$start}, {$num}" : "limit {$num}") : '';
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

//  查询指定id的记录
  public function selectIdOne($id): array
  {
    return $this->conn->query("select * from {$this->table} where id={$id}")->fetch(PDO::FETCH_ASSOC);
  }

//  删除一条记录
  public function delOne($id): bool|\PDOStatement
  {
    return $this->conn->query("delete from {$this->table} where id={$id}");
  }

//  删除多条记录
  public function delMul($id): bool|\PDOStatement
  {
    return $this->conn->query("delete from {$this->table} where id in({$id})");
  }

//  添加一条记录
  public function addOne($name, $sex, $tel, $room, $major): bool|\PDOStatement
  {
    return $this->conn->query("insert into {$this->table} values(null,'$name','$sex','$tel','$room','$major')");
  }

//  修改一条记录
  public function alterOne($id, $name, $sex, $tel, $room, $major): bool|\PDOStatement
  {
    return $this->conn->query("update {$this->table} set name='$name',sex='$sex',tel='$tel',room='$room',major='$major' where id=$id");
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
    return $this->conn->query("update {$this->admin_table} set status=0 where user='{$_SESSION['admin'][0]}'");
  }
}