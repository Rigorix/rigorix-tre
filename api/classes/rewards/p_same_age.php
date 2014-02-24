<?php
$apply_sfidante = $apply_sfidato = (
  $sfidante->getAttribute("data_nascita") != "" &&
  $sfidato->getAttribute("data_nascita") != "" &&
  date ("Y", strtotime($sfidante->getAttribute("data_nascita"))) == date ("Y", strtotime ($sfidato->getAttribute("data_nascita")))
);