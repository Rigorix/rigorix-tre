<?php
function flattenArray($array, $prefix = null, $results = array()) {
  foreach ($array as $key => $val) {
    $name = (empty($prefix)) ? $key : $prefix."[$key]";
    if (is_array($val))
      $results = array_merge($results, flattenArray($val, $name));
    else
      $results[$name] = $val;
  }
  return $results;
}

if ( isset($_COOKIE['rigorix_internal_return_url']) ) {
  $data = unserialize(base64_decode( $_POST['opauth'] ));
  $html = '<html><body onload="postit();"><form name="auth" method="post" action="'.$_COOKIE['rigorix_internal_return_url'].'">';

  if (!empty($data) && is_array($data)){
    $flat = flattenArray($data);
    foreach ($flat as $key => $value){
      $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
    }
  }
  $html .= '</form>';
  $html .= '<script type="text/javascript">function postit(){ document.auth.submit(); }</script>';
  $html .= '</body></html>';

  unset($_COOKIE['rigorix_internal_return_url']);
  echo $html;

  die();
} else
  header("Location: /#/errore-login");
