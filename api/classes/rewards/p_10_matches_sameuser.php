<?php
$apply_sfidante = $apply_sfidato =
  Sfide::today()->done()->between($sfidante->getAttribute('id_utente'), $sfidato->getAttribute('id_utente'))->count() == 10;
