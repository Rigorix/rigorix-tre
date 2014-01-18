<?php

$__DEBUG_SCRIPT = true;
chdir("../");

require_once ("classes/core.php");

$query = "SELECT id_sfidante, id_sfidato, punti_sfidante, punti_sfidato
 FROM sfida
WHERE dta_conclusa >= DATE_SUB( CURDATE( ) , INTERVAL 7
DAY )
AND stato =2";

echo "start";

$result = $dm_utente->getArrayObjectQueryCustom($query);

$usersPoints = array();

foreach ( $result as $sfida ) {
    if ( !array_key_exists($sfida->id_sfidante, $usersPoints))
        $usersPoints[$sfida->id_sfidante] = 0;
    $usersPoints[$sfida->id_sfidante] += $sfida->punti_sfidante;

    if ( !array_key_exists($sfida->id_sfidato, $usersPoints))
        $usersPoints[$sfida->id_sfidato] = 0;
    $usersPoints[$sfida->id_sfidato] += $sfida->punti_sfidato;
}

$bestUser = 0;
$bestUserPunteggio = 0;

foreach ( $usersPoints as $id_utente => $punteggio ) {
    if ( $punteggio > $bestUserPunteggio ) {
        $bestUser = $id_utente;
        $bestUserPunteggio = $punteggio;
    }
}

?>
<pre>
    <? var_dump ($usersPoints); ?>
</pre>

Best user: <?=$bestUser;?>