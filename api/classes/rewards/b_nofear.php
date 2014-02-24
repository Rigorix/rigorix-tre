<?php
$apply_sfidante = !$sfidante->rewards->contains(13) && $sfidante->getAttribute('punteggio_totale') < $sfidato->getAttribute('punteggio_totale');