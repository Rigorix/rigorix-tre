<?php

$apply_sfidante = Sfide::user($sfidante->getKey())->count() == 1;
$apply_sfidato = Sfide::user($sfidato->getKey())->count() == 1;
