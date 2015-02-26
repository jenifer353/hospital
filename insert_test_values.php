<?php
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
?>