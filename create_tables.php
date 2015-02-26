<?php
$db->Query("CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(530) NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$db->Query("CREATE TABLE IF NOT EXISTS persons (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(530) NOT NULL,
  zarplata int NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$db->Query("CREATE TABLE IF NOT EXISTS hvorobi (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(530) NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

$db->Query("CREATE TABLE IF NOT EXISTS istoria_hvorobi (
  id int NOT NULL AUTO_INCREMENT,
  id_user int NOT NULL,
  id_hvorobi int NOT NULL,
  id_person int NOT NULL,
  time_start int NOT NULL,
  time_stop int NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (id_hvorobi) REFERENCES hvorobi(id) ON DELETE CASCADE,
  FOREIGN KEY (id_person) REFERENCES persons(id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
?>