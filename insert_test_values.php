<?php

$enable_big_data = false;

$db->new_user("Алла Якубова");
$db->new_user("Андрій Яловицький");
$db->new_user("Олег Лісовський");
$db->new_user("Олекса Довгий");
$db->new_user("Олександр Груша");

$db->new_person('Леонід Причепа', rand(1000,6000));
$db->new_person('Андрій Волосюк', rand(3000,4000));
$db->new_person('Василь Захарчук', rand(8000,10000));

$db->new_hvoroba("Рак");
$db->new_hvoroba("ГРВІ");
$db->new_hvoroba("Аритмія");
$db->new_hvoroba("Діарея");
$db->new_hvoroba("Гепатит");
$db->new_hvoroba("Туберкульоз");

$db->new_entry(4, 4, 1, time()-(60*60*24*65), -1);
$db->new_entry(5, 6, 3, time()-(60*60*24*1), -1);
$db->new_entry(4, 1, 2, time()-(60*60*24*43), -1);
$db->new_entry(3, 5, 2, time()-(60*60*24*23), -1);
$db->new_entry(2, 2, 1, time()-(60*60*24*2), -1);
$db->new_entry(1, 3, 3, time()-(60*60*24*342), time()-(60*60*24*321));

if ($enable_big_data) {
  $i = 2000;
  while ($i--) $db->new_user(md5(time()+mt_rand(0,200)));
  $i = 200;
  while ($i--) $db->new_person(md5(time()+mt_rand(201,300)),mt_rand(1000,20000));
  $i = 500;
  while ($i--) $db->new_hvoroba(md5(time()+mt_rand(320,500)));
  $i = 20000;
  while ($i--) $db->new_entry(mt_rand(0,2005), mt_rand(0,506), mt_rand(0,203), time()-mt_rand(0,10000), -1);
}
?>