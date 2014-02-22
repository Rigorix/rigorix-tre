<?php
$apply_sfidante = ( time() - strtotime($sfidato->getAttribute('dta_reg'))  < 7 * 24 * 60 * 60 );