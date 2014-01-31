<?php

function hasAuth ($id_utente) {
  return ( $_SESSION['rigorix']['user'] !== false && $id_utente == $_SESSION['rigorix']['user']->id_utente);
}

?>