<?php
class dbg {
  private static $log = [];
  public static function log($str, $type='default', $title = '') {
    $callers=debug_backtrace();
    array_shift($callers);
    $count = count($callers);
    while ($count--) $title .= self::get_call_string($callers[$count]);
    self::$log[] = [$type, $title, str_replace(" ", "&nbsp;", str_replace("\n", '<br>', $str))];
  }

  private static function get_call_string($caller) {
    return '<i class="fa fa-arrow-circle-o-right"></i> '.@$caller['class'].@$caller['type'].'<b>'.$caller['function'].'( )</b> ';
  }
  
  public static function plog() {
    $tmp = '';
    foreach (self::$log as $value) {
      $tmp .= '<div class="panel panel-'.$value[0].'"><div class="panel-heading">'.$value[1].'</div><div class="panel-body">'.$value[2].'</div></div>';
    }
    return $tmp;
  }
}

function debug() {
  echo row('<hr>').div(dbg::plog(), 10, 'col-sm-offset-1');
}
?>