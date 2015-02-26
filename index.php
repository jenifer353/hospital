<?php
$body = '<div class="col-sm-10 col-sm-offset-1">';

require_once 'dbg.php';
require_once 'database.php';
require_once 'view.php';

dbg::log(print_r($_GET, true), 'success', '<b>$_GET[]</b>');
dbg::log(print_r($_POST, true), 'success', '<b>$_POST[]</b>');

require_once 'drop_all_tables.php';
require_once 'create_tables.php';
require_once 'insert_test_values.php';

@$page = $_GET['p'] or false;
@$search = $_GET['search'] or false;
@$add = $_POST['add'] or false;
@$remove = $_POST['remove'] or false;
@$user = $_GET['user'] or false;
@$person = $_GET['person'] or false;
@$hvoroba = $_GET['hvoroba'] or false;

$nav_li = [
  'users' => ['<i class="fa fa-lg fa-user"></i> Пацієнти',false],
  'persons' => ['<i class="fa fa-lg fa-user-md"></i> Лікарі',false],
  'hvorobi' => ['<i class="fa fa-lg fa-tint"></i> Хвороби',false],
  'entrys' => ['<i class="fa fa-lg fa-book"></i> Журнал',false]
];

function set_active($name) {
  $GLOBALS['nav_li'][$name][1] = true;
}
function valid($str) {
  return htmlspecialchars(trim($str));
}

if ($add) {
  if ($add === 'new_user') {
    $_POST['name'] = valid($_POST['name']);
    if ($_POST['name']) {
      $db->new_user($_POST['name']);
      header('Location:./?p=users');
    }
  }
  elseif ($add === 'new_person') {
    $_POST['name'] = valid($_POST['name']);
    $_POST['zarplata'] = valid($_POST['zarplata']);
    if ($_POST['name'] && $_POST['zarplata']) {
      $db->new_person($_POST['name'], $_POST['zarplata']);
      header('Location:./?p=persons');
    }
  }
  elseif ($add === 'new_hvoroba') {
    $_POST['name'] = valid($_POST['name']);
    if ($_POST['name']) {
      $db->new_hvoroba($_POST['name']);
      header('Location:./?p=hvorobi');
    }
  }
  elseif ($add === 'new_entry') {
    @$_POST['user'] = valid($_POST['user']);
    @$_POST['hvoroba'] = valid($_POST['hvoroba']);
    @$_POST['time_start'] = valid($_POST['time_start']);
    @$_POST['time_stop'] = valid($_POST['time_stop']);
    @$_POST['person'] = valid($_POST['person']);

    if (empty($_POST['time_start'])) $_POST['time_start'] = time();
    if (empty($_POST['time_stop'])) $_POST['time_stop'] = -1;

    if ($_POST['user'] && $_POST['hvoroba'] && $_POST['time_start'] && $_POST['time_stop'] && $_POST['person']) {
      $db->new_entry($_POST['user'], $_POST['hvoroba'], $_POST['person'], $_POST['time_start'], $_POST['time_stop']);
      header('Location:./?p=entrys');
    }
  }
}
elseif ($remove) {
  $remove = str_replace('remove_', '', $remove);
  $p = $remove.'s';
  if ($remove === 'hvoroba') $p = 'hvorobi';
  $_POST['id'] = (int) valid($_POST['id']);
  if ($_POST['id']) {
    $db->remove($remove, $_POST['id']);
    header('Location:./?p='.$p);
  }
}

if ($page) {
  if ($page === 'users') {
    $body .= table_users();
    set_active($page);
  }

  elseif ($page === 'persons') {
    $body .= table_persons();
    set_active($page);
  }

  elseif ($page === 'hvorobi') {
    $body .= table_hvorobi();
    set_active($page);
  }

  elseif ($page === 'entrys') {
    $body .= table_entrys();
    set_active($page);
  }
}

elseif ($search) {
  $msg = "<h1><i class=\"fa fa-search\"></i> Пошук за запитом '$search'</h1>";
  $search_ok = false;
  $search = valid($search);
  $search_data = $db->search_users($search);
  if ($search_data){
    $search_ok = true;
    $msg .= table_users(12,$search_data);
  }
  $search_data = $db->search_persons($search);
  if ($search_data){
    $search_ok = true;
    $msg .= table_persons(12,$search_data);
  }
  $search_data = $db->search_hvorobi($search);
  if ($search_data){
    $search_ok = true;
    $msg .= table_hvorobi(12,$search_data);
  }
  if (!$search_ok) $body .= "<h1>Нажаль по запиту \"$search\" нічого не знайшлось</h1>";
  else $body .= $msg;
}

elseif ($user) {
  set_active('users');
  $usr = $db->get_user($user);
  if (!empty($usr)) $body .= table_entrys_by_user($usr);
  else $body .= error404("user");
}

elseif ($person) {
  set_active('persons');
  $persn = $db->get_person($person);
  if (!empty($persn)) $body .= table_users_by_person($persn);
  else $body .= error404('person');
}

elseif ($hvoroba) {
  set_active('hvorobi');
  $hvrb = $db->get_hvoroba($hvoroba);
  if (!empty($hvrb)) $body .= table_users_by_hvoroba($hvrb);
  else $body .= error404('hvoroba');
}

else {
  $body = '<div class="col-sm-12">';
  $body .= table_persons(4);
  $body .= table_users(4);
  $body .= table_hvorobi(4);
  $body .= table_entrys();
}

$body .= '</div>';
?>
<!DOCTYPE html>
<html>
<head>
  <title>alla</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
      <a class="navbar-brand" href="./"><i class="fa fa-lg fa-hospital-o"></i> БД "Лікарня"</a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
<?php
foreach ($nav_li as $k => $v) {
  $a = $v[1] ? 'class="active-my"' : '';
  echo '<li '.$a.'><a href="./?p='.$k.'">'.$v[0].'</a></li>';
}
?>
      </ul>
      <form class="navbar-form navbar-right" role="form" method="get">
        <div class="form-group">
          <input type="text" placeholder="Пошук" class="form-control" name="search" <?php
            if ($search) echo 'value="'.$search.'"';
          ?>>
        </div>
        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>
</nav>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
echo $body;
debug();
require_once "forms.php";
?>
</body>
</html>