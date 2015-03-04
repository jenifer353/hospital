<?php
class Database {
  public function __construct() {
    $this->mysql = new mysqli("localhost", "root", "", "test");
    $this->mysql->Query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
  }

  public function remove($name, $id) {
    $table = $name.'s';
    if ($name === 'hvoroba') $table = 'hvorobi';
    if ($name === 'entry') $table = 'istoria_hvorobi';
    if (is_int($id)) $this->Query("DELETE FROM $table WHERE id='$id'");
  }

  public function new_user($name) {
    $this->Query("INSERT INTO users\nVALUES (NULL, '$name')");
  }

  public function new_person($name, $zarplata) {
    $this->Query("INSERT INTO persons\nVALUES (NULL, '$name', $zarplata)");
  }

  public function new_hvoroba($name) {
    $this->Query("INSERT INTO hvorobi\nVALUES (NULL, '$name')");
  }
  public function new_entry($id_user, $id_hvorobi, $id_person, $time_start, $time_stop) {
    $this->Query("INSERT INTO istoria_hvorobi\nVALUES (NULL, '$id_user', '$id_hvorobi', '$id_person', '$time_start', '$time_stop')");
  }

  public function drop_table($name) {
    $this->Query("DROP TABLE $name");
  }

  public function get_users() {
    return $this->get('users');
  }

  public function get_persons() {
    return $this->get('persons');
  }

  public function get_hvorobi() {
    return $this->get('hvorobi');
  }

  public function get_user($id) {
    $tmp = $this->get('users',"id='$id'");
    if (empty($tmp)) return false;
    return $tmp[0];
  }

  public function get_person($id) {
    $tmp = $this->get('persons',"id='$id'");
    if (empty($tmp)) return false;
    return $tmp[0];
  }

  public function get_hvoroba($id) {
    $tmp = $this->get('hvorobi',"id='$id'");
    if (empty($tmp)) return false;
    return $tmp[0];
  }

  public function search_users($keyword) {
    return $this->get('users',"id LiKE '%$keyword%'
      OR name LIKE '%$keyword%'");
  }

  public function search_persons($keyword) {
    return $this->get('persons',"id LiKE '%$keyword%'
      OR name LIKE '%$keyword%'");
  }

  public function search_hvorobi($keyword) {
    return $this->get('hvorobi',"id LiKE '%$keyword%'
      OR name LIKE '%$keyword%'");
  }

  public function get_entrys() {
    return $this->get('istoria_hvorobi, users, hvorobi, persons',
      "istoria_hvorobi.id_user = users.id AND
      istoria_hvorobi.id_person = persons.id AND
      istoria_hvorobi.id_hvorobi = hvorobi.id",
      'time_start, time_stop,
      istoria_hvorobi.id AS id,
      users.id AS user_id,
      persons.id AS person_id,
      hvorobi.id AS hvoroba_id,
      users.name AS user,
      hvorobi.name AS hvoroba,
      persons.name AS person');
  }

  public function get_entry($id) {
    $tmp = $this->get('istoria_hvorobi, users, hvorobi, persons',
      "istoria_hvorobi.id = '$id' AND
      istoria_hvorobi.id_user = users.id AND
      istoria_hvorobi.id_person = persons.id AND
      istoria_hvorobi.id_hvorobi = hvorobi.id",
      'time_start, time_stop,
      istoria_hvorobi.id AS id,
      users.name AS user,
      hvorobi.name AS hvoroba,
      persons.name AS person');
    if (empty($tmp)) return false;
    return $tmp[0];
  }

  public function get_entrys_by_user($id) {
    return $this->get('istoria_hvorobi, users, hvorobi, persons',
      "istoria_hvorobi.id_user = users.id AND
      istoria_hvorobi.id_user = '$id' AND
      istoria_hvorobi.id_person = persons.id AND
      istoria_hvorobi.id_hvorobi = hvorobi.id",
      'time_start, time_stop,
      istoria_hvorobi.id AS id,
      persons.id AS person_id,
      hvorobi.id AS hvoroba_id,
      hvorobi.name AS hvoroba,
      persons.name AS person');
  }

  public function get_from_entry($name, $id) {
    if ($name === 'hvoroba') $name = $table = 'hvorobi';
    else $table = $name.'s';
    $tmp = $this->get('istoria_hvorobi, '.$table,
      "istoria_hvorobi.id = '$id' AND
      istoria_hvorobi.id_$name = $table.id",
      "$table.id AS id");
    if (empty($tmp)) return false;
    return $tmp[0];
  }

  public function get_users_by_person($id) {
    return $this->get('istoria_hvorobi, users, hvorobi',
      "istoria_hvorobi.id_person = '$id' AND
      istoria_hvorobi.time_stop = '-1' AND
      istoria_hvorobi.id_hvorobi = hvorobi.id AND
      istoria_hvorobi.id_user = users.id",
      "istoria_hvorobi.id AS id,
      users.id AS user_id,
      hvorobi.id AS hvoroba_id,
      users.name AS name,
      hvorobi.name AS hvoroba,
      istoria_hvorobi.time_start AS time_start");
  }

  public function get_users_by_hvoroba($id) {
    return $this->get('istoria_hvorobi, users, persons',
      "istoria_hvorobi.id_hvorobi = '$id' AND
      istoria_hvorobi.time_stop = '-1' AND
      istoria_hvorobi.id_person = persons.id AND
      istoria_hvorobi.id_user = users.id",
      "istoria_hvorobi.id AS id,
      users.id AS user_id,
      persons.id AS person_id,
      users.name AS name,
      persons.name AS person,
      istoria_hvorobi.time_start AS time_start");
  }

  public function Query($str) {
    dbg::log($str, 'info');
    return $this->mysql->Query($str);
  }

  public function get($table, $k = '', $f = '*') {
    $temp = [];
    if ($k !== '') $k = "\nWHERE " . $k;
    $res = $this->Query("SELECT $f \nFROM $table $k \nORDER BY id");
    if (is_object($res)) for ($i = 0; $i < $res->num_rows; $i++) $temp[$i] = $res->fetch_assoc();
    return $temp;
  }
}
$db = new Database;
?>