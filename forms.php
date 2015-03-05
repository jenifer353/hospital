<?php

$data = [
  "new_user" => [
    'action' => 'add',
    'title' => "Додати нового пацієнта",
    'input' => [
      [
        'name' => 'name',
        'type' => 'text',
        'title' => 'Ім\'я',
        'placeholder' => 'Ім\'я пацієнта'
      ]
    ]
  ],
  "new_person" => [
    'action' => 'add',
    'title' => "Додати нового лікаря",
    'input' => [
      [
        'name' => 'name',
        'type' => 'text',
        'title' => 'Ім\'я',
        'placeholder' => 'Ім\'я лікаря'
      ],
      [
        'name' => 'zarplata',
        'type' => 'number',
        'title' => 'Зарплата',
        'placeholder' => 'Зарплата лікаря'
      ]
    ]
  ],
  "new_hvoroba" => [
    'action' => 'add',
    'title' => "Додати нову хворобу",
    'input' => [
      [
        'name' => 'name',
        'type' => 'text',
        'title' => 'Назва',
        'placeholder' => 'Назва хвороби'
      ]
    ]
  ],
  "new_entry" => [
    'action' => 'add',
    'title' => "Додати новий запис журналу",
    'input' => [
      [
        'name' => 'user',
        'type' => 'select',
        'title' => 'Ім\'я пацієнта',
        'options' => $db->get_users()
      ],
      [
        'name' => 'hvoroba',
        'type' => 'select',
        'title' => 'Назва хвороби',
        'options' => $db->get_hvorobi()
      ],
      [
        'name' => 'time_start',
        'type' => 'date',
        'title' => 'Час з якого на лікуванні'
      ],
      [
        'name' => 'time_stop',
        'type' => 'date',
        'title' => 'Час по який на лікуванні'
      ],
      [
        'name' => 'person',
        'type' => 'select',
        'title' => 'Ім\'я лікаря',
        'options' => $db->get_persons()
      ]
    ]
  ],
  "remove_user" => [
    'action' => 'remove',
    'title' => "Видалення пацієнта зі списку",
    'input' => "Ви дійсно бажаєте вилалити пацієнта <b>NULL</b> з бази даних?"
  ],
  "remove_person" => [
    'action' => 'remove',
    'title' => "Видалення лікаря зі списку",
    'input' => "Ви дійсно бажаєте вилалити лікаря <b>NULL</b>?"
  ],
  "remove_hvoroba" => [
    'action' => 'remove',
    'title' => "Видалення хвороби зі списку",
    'input' => "Ви дійсно бажаєте вилалити хворобу <b>NULL</b>?"
  ],
  "remove_entry" => [
    'action' => 'remove',
    'title' => "Видалення запису з історії хвороби",
    'input' => "Ви дійсно бажаєте видалити запис №<b>NULL</b> з журналу?"
  ]
];

foreach ($data as $key => $value) {
  $btn_text = 'OK';
  if ($value['action'] === 'add') $btn_text = 'Зберегти';
  if ($value['action'] === 'remove') $btn_text = 'Видалити';

  echo '<div class="modal fade" id="'.$key.'">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">'.$value['title'].'</h4>
      </div>
        <form class="form-horizontal" role="form" method="post">
        <div class="modal-body">';

  if (is_array($value['input'])) foreach ($value['input'] as $v) {
    echo '<div class="form-group">
            <label for="input_'.$key.'_'.$v['name'].'" class="col-sm-5 control-label">'.$v['title'].'</label>
            <div class="col-sm-6">';
    if ($v['type'] === 'select') {
      echo '<select name="'.$v['name'].'" id="input_'.$key.'_'.$v['name'].'">';
      foreach ($v['options'] as $option) echo '<option value="'.$option['id'].'">'.$option['name'].'</option>';
      echo '</select>';
    }
    else {
      echo '<input type="'.$v['type'].'" class="form-control" id="input_'.$key.'_'.$v['name'].'"';
      if (isset($v['placeholder'])) echo  'placeholder="'.$v['placeholder'].'"';
      echo ' name="'.$v['name'].'">';
    }
    echo '</div></div>';
  }
  else {
    echo '<div class="alert alert-danger">'.$value['input'].'</div>';
    echo '<input type="hidden" name="id" id="'.$value['action'].'_'.$key.'_id">';
  }

  echo '</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Відміна</button>
          <button type="submit" name="'.$value['action'].'" value="'.$key.'" class="btn btn-primary">'.$btn_text.'</button>
        </div>
      </form>
    </div>
  </div>
</div>';
  $tmp_t = 'row.childNodes[1].firstChild.innerText';
  if ($key == 'remove_entry') $tmp_t = 'row.childNodes[0].innerText';
  if ($value['action'] == 'remove'){
    echo '
  <script type="text/javascript">
  $("#'.$key.'").on("show.bs.modal", function (e) {
    window.input = document.getElementById("'.$value['action'].'_'.$key.'_id");
    row = window.element[1].parentElement.parentElement;
    input.value = window.element[0];
    input.parentElement.firstChild.getElementsByTagName("b")[0].innerText = '.$tmp_t.';
  });
  </script>';
  }
}
?>