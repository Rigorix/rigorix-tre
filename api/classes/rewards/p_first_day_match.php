<?php

$apply_sfidante = Sfide::user($sfidante->getAttribute("id_utente"))->today()->count() == 1;
$apply_sfidato = Sfide::user($sfidato->getAttribute("id_utente"))->today()->count() == 1;