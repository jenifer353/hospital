<?php
function new_table($name, $names, $data, $width=12) {
  $table = '<h2>'.$name.'</h2><table class="table table-striped"><thead><tr>';
  $_data = array_fill(0,count($data),'');
  foreach ($names as $key => $value) {
    if (is_array($value)) {
      $table .= '<th>'.$value[0].'</th>';
      for ($i=0; $i<count($data); $i++) {
        if (!isset($value[2])) $value[2] = 'id';

        if (substr($key, 0, 5) === 'time_') {
          if ($data[$i][$key] == -1) $data[$i][$key] = "сьогодні";
          else $data[$i][$key] = date("d/m/Y",$data[$i][$key]);
        }
        if ($key === 'change') {
          $data[$i][$key] = '';
          foreach ($value[1] as $k => $v) $data[$i][$key] .= '<button onclick="window.element=['.$data[$i][$value[2]].',this];" class="btn btn-xs btn-'.$v[1].' btn-change" data-toggle="modal" data-target="#'.$k.'_'.$v[0].'"><i class="fa fa-'.$k.'"></i><a></button>';
        }
        elseif (isset($value[1])) {
          $data[$i][$key] = '<a href="./?'.$value[1].'='.$data[$i][$value[2]].'">'.$data[$i][$key].'</a>';
        }
        $_data[$i] .= "<td>".$data[$i][$key]."</td>";
      }
    }
  }
  $table .= '</tr></thead><tbody>';
  foreach ($_data as $value) $table .= '<tr>'.$value.'<tr>';
  $table .= '</tbody></table>';
  return div($table, $width, 'table-responsive');
}

function row($str, $width=12) {
  return div($str, $width, 'row');
}

function div($str, $width=12, $class='') {
  return '<div class="col-sm-'.$width.' '.$class.'">'.$str.'</div>';
}

function table_persons($width = 12, $data = false) {
  if (!$data) $data = $GLOBALS['db']->get_persons();
  return new_table('<i class="fa fa-user-md"></i> Персонал <button class="btn btn-success add" data-toggle="modal" data-target="#new_person"><i class="fa fa-plus"></i></button>',
      [
        'id' => ['ID'],
        'name' => ['<i class="fa fa-user-md"></i> Ім\'я','person'],
        'zarplata' => ['<i class="fa fa-money"></i> Зарплата'],
        'change' => ['<i class="fa fa-user"></i> Дії', 
          [
            // 'edit' => ['person', 'primary'],
            'remove' => ['person', 'danger']
          ]
        ]
      ],
      $data,$width);
}

function table_users($width = 12, $data = false) {
  if (!$data) $data = $GLOBALS['db']->get_users();
  return new_table('<i class="fa fa-user"></i> Пацієнти <button class="btn btn-success add" data-toggle="modal" data-target="#new_user"><i class="fa fa-plus"></i></button>',
      [
        'id' => ['ID'],
        'name' => ['<i class="fa fa-user"></i> Ім\'я','user'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['user', 'danger']
          ]
        ]
      ],
      $data,$width);
}

function table_hvorobi($width = 12, $data = false) {
  if (!$data) $data = $GLOBALS['db']->get_hvorobi();
  return new_table('<i class="fa fa-tint"></i> Хвороби <button class="btn btn-success add" data-toggle="modal" data-target="#new_hvoroba"><i class="fa fa-plus"></i></button>',
      [
        'id' => ['ID'],
        'name' => ['<i class="fa fa-tint"></i> Назва','hvoroba'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['hvoroba', 'danger']
          ]
        ]
      ],
      $data,$width);
}

function table_entrys($width = 12) {
  return new_table('<i class="fa fa-book"></i> Історія хвороби <button class="btn btn-success add" data-toggle="modal" data-target="#new_entry"><i class="fa fa-plus"></i></button>',
      [
        'id' => ['ID'],
        'user_id' => false,
        'person_id' => false,
        'hvoroba_id' => false,
        'user' => ['<i class="fa fa-user"></i> Пацієнт','user','user_id'],
        'hvoroba' => ['<i class="fa fa-tint"></i> Хвороба','hvoroba','hvoroba_id'],
        'time_start' => ['<i class="fa fa-clock-o"></i> з'],
        'time_stop' => ['<i class="fa fa-clock-o"></i> по'],
        'person' => ['<i class="fa fa-user-md"></i> Лікар','person','person_id'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['entry', 'danger']
          ]
        ]
      ],
      $GLOBALS['db']->get_entrys(),$width);
}

function table_entrys_by_user($data) {
  $tmp = $GLOBALS['db']->get_entrys_by_user($data['id']);
  if (empty($tmp)) return '<h1>У пацієнта '.$data['name'].' жодного запису в історії хвороби<h1>';
  return new_table('<i class="fa fa-book"></i> Історія хвороби пацієнта '.$data['name'],
      [
        'id' => ['ID'],
        'hvoroba' => ['<i class="fa fa-tint"></i> Хвороба','entry=hvoroba&id'],
        'time_start' => ['<i class="fa fa-clock-o"></i> з'],
        'time_stop' => ['<i class="fa fa-clock-o"></i> по'],
        'person' => ['<i class="fa fa-user-md"></i> Лікар','entry=person&id'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['entry', 'danger']
          ]
        ]
      ],
      $tmp);
}

function table_users_by_person($data) {
  $tmp = $GLOBALS['db']->get_users_by_person($data['id']);
  if (empty($tmp)) return '<h1>'.$data['name'].' нікого не лікує<h1>';
  return new_table('<i class="fa fa-user"></i> Список кого лікує '.$data['name'].' <small> його зарплата '.$data['zarplata'].'грн</small>',
    [
      'id' => ['ID'],
      'user_id' => false,
      'hvoroba_id' => false,
      'name' => ['<i class="fa fa-user"></i> Ім\'я','user','user_id'],
      'time_start' => ['<i class="fa fa-clock-o"></i> з'],
      'hvoroba' => ['<i class="fa fa-tint"></i> Хвороба','hvoroba','hvoroba_id'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['entry', 'danger', 'hvoroba_id']
          ]
        ]
    ],
    $tmp);
}

function table_users_by_hvoroba($data) {
  $tmp = $GLOBALS['db']->get_users_by_hvoroba($data['id']);
  if (empty($tmp)) return '<h1>На хворобу "'.$data['name'].'" ніхто не хворіє<h1>';
  return new_table('<i class="fa fa-user"></i> Перелік хворих на "'.$data['name'].'"',
    [
      'id' => ['ID'],
      'user_id' => false,
      'person_id' => false,
      'name' => ['<i class="fa fa-user"></i> Ім\'я','user','user_id'],
      'time_start' => ['<i class="fa fa-clock-o"></i> з'],
      'person' => ['<i class="fa fa-user-md"></i> Лікар','person','person_id'],
        'change' => ['<i class="fa fa-cog"></i> Дії', 
          [
            'remove' => ['entry', 'danger', 'user_id']
          ]
        ]
    ],
    $tmp);
}

function error404($param = false) {
  $msg = '<h2 class="text-warning">Помилка 404</h2>';
  if ($param) {
    switch ($param) {
      case 'user':
        return $msg.'<h1>Нажаль такого пацієнта немає <i class="fa fa-meh-o"></i></h1>';
      case 'hvoroba':
        return $msg.'<h1>Нажаль такої хвороби немає <i class="fa fa-meh-o"></i></h1>';
      case 'person':
        return $msg.'<h1>Нажаль такого лікаря немає <i class="fa fa-meh-o"></i></h1>';
      case 'entry':
        return $msg.'<h1>Нажаль такого запису не існує <i class="fa fa-meh-o"></i></h1>';
      default:
        return $msg;
    }
  }
  else return $msq.'<h1>Нажаль нічого не знайдено <i class="fa fa-meh-o"></i></h1>';
}
?>