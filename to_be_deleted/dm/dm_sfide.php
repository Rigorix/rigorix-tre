<?php

// STATO DELLE SFIDE:
// 0 in attesa, appena lanciata
// 1 chiusa (giocata)
// 2 rifiutata dallo sfidato
// 3 ritirata dallo sfidante
// 4 Annullata per mancanza credito
// 5 accettata (come conclusa ma in attesa della chiusura)
//
// TIPOLOGIA DELLE SFIDE:
// 0 Bonus
// 1 Dollarix
//
// TIPO ALERT:
// 0 Tutti
// 1 Digest giornaliero
// 2 Digest ogni 3 giorni
// 3 Nessuno

class dm_sfide extends dm_generic_mysql
{

    function dm_sfide($db_conn, $db_name, $debug = 0)
    {
        $this->dm_generic_mysql($db_conn, $db_name, $debug);
    }

    function getSfideAttiveUtente($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select * from sfida where (id_sfidante = $id_utente or id_sfidato = $id_utente) and stato < 2 order by dta_sfida desc");
    }

    function getSfideUtenteToday($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select * from sfida where ((id_sfidante = $id_utente and DATE(dta_sfida) = CURDATE()) or (id_sfidato = $id_utente and DATE(dta_conclusa) = CURDATE() and stato = 2))");
    }

    function getSfideGiornaliereConcluseTraStessiUtenti($id_sfidante, $id_sfidato)
    {
        _log("----", "SELECT * FROM sfida where ( (id_sfidante = $id_sfidante and id_sfidato = $id_sfidato) or (id_sfidante = $id_sfidato and id_sfidato = $id_sfidante) ) and stato = 2 and DATE(dta_sfida) = CURDATE() and DATE(dta_conclusa) = CURDATE()");
        return $this->getArrayObjectQueryCustom("SELECT * FROM sfida where ( (id_sfidante = $id_sfidante and id_sfidato = $id_sfidato) or (id_sfidante = $id_sfidato and id_sfidato = $id_sfidante) ) and stato = 2 and DATE(dta_sfida) = CURDATE() and DATE(dta_conclusa) = CURDATE()");
    }

    function get8HoursSfide($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select EXTRACT(HOUR FROM dta_sfida) as ORA, count(*) as tot from sfida where id_sfidante = $id_utente and DATE(dta_sfida) = CURDATE() group by EXTRACT(HOUR FROM dta_sfida) order by ORA DESC");
    }

    function updatePunteggiVinti($sfida, $punteggio_sfidante, $punteggio_sfidato)
    {
        $obj = array(
            "indb_punti_sfidante" => $punteggio_sfidante,
            "indb_punti_sfidato" => $punteggio_sfidato
        );
        $obj_indb = $this->makeInDbObject($obj);
        $this->updateObject('sfida', $obj_indb, array("id_sfida" => $sfida->id_sfida));
    }

    function getOpenSfideByUtente($id_utente, $num_limit = 0)
    {
        if ($num_limit > 0) {
            return $this->getArrayObjectQueryCustom("SELECT * FROM sfida WHERE (id_sfidante=$id_utente OR id_sfidato=$id_utente) AND (STATO=0 OR STATO=5) ORDER BY dta_sfida DESC LIMIT 0,$num_limit");
        } else {
            return $this->getArrayObjectQueryCustom("SELECT * FROM sfida WHERE (id_sfidante=$id_utente OR id_sfidato=$id_utente) AND (STATO=0 OR STATO=5) ORDER BY dta_sfida DESC");
        }
    }

    function getAvversarioByIdSfidaAndIdUtente($id_sfida, $id_utente)
    {
        global $dm_utente;

        $sfida = $this->getSingleObjectQueryCustom("SELECT id_sfidante, id_sfidato from sfida where id_sfida = $id_sfida");
        $id_sfidante = (($sfida->id_sfidante == $id_utente) ? $sfida->id_sfidato : $sfida->id_sfidante);

        return $dm_utente->getObjUtenteById($id_sfidante);
    }

    function getSfidaById($id_sfida)
    {
        return $this->getSingleObjectQueryCustom("SELECT * from sfida where id_sfida = $id_sfida");
    }

    function getSfideNonNotificateByIdUtente($id_sfidante)
    {
        return $this->getArrayObjectQueryCustom("SELECT *, (select username from utente where utente.id_utente = sfida.id_sfidato) as username_avversario from sfida where stato = 2 and notifica = 0 and id_sfidante = $id_sfidante");
    }

    function updateSfidaNotificata($id_sfida)
    {
        return $this->getArrayObjectQueryCustom("update sfida set notifica = 1 where id_sfida = $id_sfida");
    }

    function getObjTiriAndParate($id_sfida, $id_utente)
    {

        $res->tiri = $this->getSingleObjectQueryCustom("SELECT o1,o2,o3,o4,o5 FROM tiri WHERE id_sfida=$id_sfida AND id_utente=$id_utente");
        $res->parate = $this->getSingleObjectQueryCustom("SELECT o1,o2,o3,o4,o5 FROM parate WHERE id_sfida=$id_sfida AND id_utente=$id_utente");
        return $res;
    }

    // ID_VINCITORE 0 se pareggio
    function getFullObjSfidaById($id_sfida)
    {
        global $dm_utente;

        $obj = $this->getSingleObjectQueryCustom("SELECT * FROM sfida WHERE id_sfida=$id_sfida");
        $obj->SFIDANTE = $this->getObjTiriAndParate($obj->id_sfida, $obj->id_sfidante);
        $obj->SFIDATO = $this->getObjTiriAndParate($obj->id_sfida, $obj->id_sfidato);

        $risultati = $this->getRisultato($obj->SFIDANTE, $obj->SFIDATO);

        $obj->SFIDANTE->risultato = $risultati->ris1;
        $obj->SFIDATO->risultato = $risultati->ris2;
        if ($risultati->ris1 > $risultati->ris2)
            $obj->VINCITORE = $obj->id_sfidante;
        else if ($risultati->ris1 == $risultati->ris2) {
            $obj->tipo_competizione = 0;
            if ($obj->tipo_competizione == 0)
                $obj->VINCITORE = 0;
            else {
                // E' una sfida playoff quindi non ci sono pareggi.
                // Vince il giocatore più anziano
                $obj->VINCITORE = $dm_utente->getIdUtenteAnziano($obj->id_sfidante, $obj->id_sfidato);
            }
        } else
            $obj->VINCITORE = $obj->id_sfidato;

        return $obj;
    }

    function getIdUtenteBest()
    {
        $obj = $this->getSingleObjectQueryCustom("select id_utente from utente where attivo = 1 order by punteggio_totale desc limit 1");
        return $obj->id_utente;
    }

    function getIdUtenteWeekBest()
    {
        $obj = $this->getArrayObjectQueryCustom("SELECT id_sfidante, id_sfidato, punti_sfidante, punti_sfidato
                                                    FROM sfida
                                                    WHERE dta_conclusa >= DATE_SUB( CURDATE( ) , INTERVAL 7
                                                    DAY )
                                                    AND stato =2");
        $usersPoints = array();

        foreach ( $obj as $sfida ) {
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
        return json_encode( array( "id" => $bestUser, "punteggio" => $bestUserPunteggio ));
    }

    function getSfideDaGiocareByUtente($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select * from sfida where ( (id_sfidante = $id_utente and stato = 0) or (id_sfidato = $id_utente and stato = 1)) and stato < 2");
    }

    function getSfideVinteByIdUtente($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select * from sfida where id_vincitore = $id_utente");
    }

    function getSfideConcluseByIdUtente($id_utente)
    {
        return $this->getArrayObjectQueryCustom("select * from sfida where ( id_sfidante = $id_utente or id_sfidato = $id_utente ) and stato = 2");
    }

    function getPuntiFromSfideByIdUtente($id_utente)
    {
        $puntiTot = 0;
        $sfideUtenteConcluse = $this->getSfideConcluseByIdUtente($id_utente);
        foreach ($sfideUtenteConcluse as $sfida) {
            if ($sfida->id_vincitore == $id_utente)
                $puntiTot += 3;
            if ($sfida->id_vincitore == 0)
                $puntiTot += 1;
        }
        return $puntiTot;
    }

    function getCountSfideDaGiocareByUtente($id_utente)
    {
        $ret = $this->getSingleObjectQueryCustom("select count(*) as tot from sfida where ( (id_sfidante = $id_utente and stato = 0) or (id_sfidato = $id_utente and stato = 1)) and stato < 2");
        return $ret->tot;
    }

    function findReverseSfidaId($id_sfida)
    {
        // Trova la sfida inversa, cioè data quella di un utente, trova la corrispondente al contrario
        $sfida = $this->getSfidaById($id_sfida);
        $sfida_rev = $this->getSingleObjectQueryCustom("select * from sfida where id_sfidante = " . $sfida->id_sfidato . " and id_sfidato = " . $sfida->id_sfidante);
        if (isset ($sfida_rev->id_sfida))
            return $sfida_rev->id_sfida;
        else
            return false;
    }

    function getRisultato($obj1, $obj2)
    {
        // 1 tira 2 para
        $ris1 = 0;
        $ris2 = 0;

        // CHECK 1
        if ($obj1->tiri->o1 != $obj2->parate->o1) {
            //if ( $obj2->parate->o1[0]!=$obj1->tiri->o1 && $obj2->parate->o1[1]!=$obj1->tiri->o1 )
            $ris1++;
        }
        if ($obj1->tiri->o2 != $obj2->parate->o2) {
            //if ( $obj2->parate->o2[0]!=$obj1->tiri->o2 && $obj2->parate->o2[1]!=$obj1->tiri->o2 )
            $ris1++;
        }
        if ($obj1->tiri->o3 != $obj2->parate->o3) {
            //if ( $obj2->parate->o3[0]!=$obj1->tiri->o3 && $obj2->parate->o3[1]!=$obj1->tiri->o3 )
            $ris1++;
        }
        if ($obj1->tiri->o4 != $obj2->parate->o4) {
            //if ( $obj2->parate->o4[0]!=$obj1->tiri->o4 && $obj2->parate->o4[1]!=$obj1->tiri->o4 )
            $ris1++;
        }
        if ($obj1->tiri->o5 != $obj2->parate->o5) {
            //if ( $obj2->parate->o5[0]!=$obj1->tiri->o5 && $obj2->parate->o5[1]!=$obj1->tiri->o5 )
            $ris1++;
        }

        // CHECK 2
        if ($obj2->tiri->o1 != $obj1->parate->o1) {
            //if ( $obj1->parate->o1[0]!=$obj2->tiri->o1 && $obj1->parate->o1[1]!=$obj2->tiri->o1 )
            $ris2++;
        }
        if ($obj2->tiri->o2 != $obj1->parate->o2) {
            //if ( $obj1->parate->o2[0]!=$obj2->tiri->o2 && $obj1->parate->o2[1]!=$obj2->tiri->o2 )
            $ris2++;
        }
        if ($obj2->tiri->o3 != $obj1->parate->o3) {
            //if ( $obj1->parate->o3[0]!=$obj2->tiri->o3 && $obj1->parate->o3[1]!=$obj2->tiri->o3 )
            $ris2++;
        }
        if ($obj2->tiri->o4 != $obj1->parate->o4) {
            //if ( $obj1->parate->o4[0]!=$obj2->tiri->o4 && $obj1->parate->o4[1]!=$obj2->tiri->o4 )
            $ris2++;
        }
        if ($obj2->tiri->o5 != $obj1->parate->o5) {
            //if ( $obj1->parate->o5[0]!=$obj2->tiri->o5 && $obj1->parate->o5[1]!=$obj2->tiri->o5 )
            $ris2++;
        }

        $obj->ris1 = $ris1;
        $obj->ris2 = $ris2;
        return $obj;
    }

    function getDetailsUtente($id_utente)
    {
        $obj = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE id_utente=$id_utente");

        $objSfideGiocate = $this->getSingleObjectQueryCustom("SELECT count(*) AS tt FROM sfida WHERE (id_sfidante=$id_utente OR id_sfidato=$id_utente) AND stato=1");
        $numSfideGiocate = $objSfideGiocate->tt;

        $objSfideVinte = $this->getSingleObjectQueryCustom("SELECT count(*) AS tt FROM sfida WHERE (id_sfidante=$id_utente OR id_sfidato=$id_utente) AND id_vincitore=$id_utente AND stato=1");
        $numSfideVinte = $objSfideVinte->tt;

        $objSfidePerse = $this->getSingleObjectQueryCustom("SELECT count(*) AS tt FROM sfida WHERE(id_sfidante=$id_utente OR id_sfidato=$id_utente) AND id_vincitore>0 AND id_vincitore<>$id_utente AND stato=1");
        $numSfidePerse = $objSfidePerse->tt;

        if ($numSfideGiocate > 0) {
            $percVittorie = $numSfideVinte / ($numSfideGiocate / 100);
        } else {
            $percVittorie = 0;
        }

        if ($numSfideGiocate > 0) {
            $percSconfitte = $numSfidePerse / ($numSfideGiocate / 100);
        } else {
            $percSconfitte = 0;
        }

        $obj->percVittorie = $percVittorie;
        $obj->percSconfitte = $percSconfitte;

        $obj1 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=1 AND id_utente=$id_utente) as TOT1
FROM tiri WHERE id_utente=$id_utente");

        $obj2 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=2 AND id_utente=$id_utente) as TOT2
FROM tiri WHERE id_utente=$id_utente");

        $obj3 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM tiri WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=3 AND id_utente=$id_utente) as TOT3
FROM tiri WHERE id_utente=$id_utente");

        if (isset($obj1->TOT1)) {
            $totSinistra = $obj1->TOT1;
        } else {
            $totSinistra = 0;
        }

        if (isset($obj2->TOT2)) {
            $totAlto = $obj2->TOT2;
        } else {
            $totAlto = 0;
        }

        if (isset($obj3->TOT3)) {
            $totDestra = $obj3->TOT3;
        } else {
            $totDestra = 0;
        }

        $totTiri = $totSinistra + $totAlto + $totDestra;
        if ($totTiri > 0) {
            $percSinistra = $totSinistra / ($totTiri / 100);
            $percAlto = $totAlto / ($totTiri / 100);
            $percDestra = $totDestra / ($totTiri / 100);
        } else {
            $percSinistra = 0;
            $percAlto = 0;
            $percDestra = 0;
        }

        $obj->percTiriSinistra = $percSinistra;
        $obj->percTiriAlto = $percAlto;
        $obj->percTiriDestra = $percDestra;


        // PARATE

        $obj1 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=1 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=1 AND id_utente=$id_utente) as TOT1
FROM parate WHERE id_utente=$id_utente");

        $obj2 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=2 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=2 AND id_utente=$id_utente) as TOT2
FROM parate WHERE id_utente=$id_utente");

        $obj3 = $this->getSingleObjectQueryCustom("SELECT distinct id_utente,
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o1=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o2=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o3=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o4=3 AND id_utente=$id_utente)+
(SELECT COUNT(*) FROM parate WHERE id_sfida IN (SELECT id_sfida FROM sfida WHERE stato=1 ) AND o5=3 AND id_utente=$id_utente) as TOT3
FROM parate WHERE id_utente=$id_utente");

        if (isset($obj1->TOT1)) {
            $totSinistra = $obj1->TOT1;
        } else {
            $totSinistra = 0;
        }

        if (isset($obj2->TOT2)) {
            $totAlto = $obj2->TOT2;
        } else {
            $totAlto = 0;
        }

        if (isset($obj3->TOT3)) {
            $totDestra = $obj3->TOT3;
        } else {
            $totDestra = 0;
        }

        $totTiri = $totSinistra + $totAlto + $totDestra;
        if ($totTiri > 0) {
            $percSinistra = $totSinistra / ($totTiri / 100);
            $percAlto = $totAlto / ($totTiri / 100);
            $percDestra = $totDestra / ($totTiri / 100);
        } else {
            $percSinistra = 0;
            $percAlto = 0;
            $percDestra = 0;
        }

        $obj->percParateSinistra = $percSinistra;
        $obj->percParateAlto = $percAlto;
        $obj->percParateDestra = $percDestra;


        // Ultima Partita
        $arrObjLast = $this->getArrayObjectQueryCustom("SELECT * FROM sfida WHERE (id_sfidante=$id_utente OR id_sfidato=$id_utente) AND stato=1 ORDER BY dta_sfida DESC");
        $obj->lastSfida = $arrObjLast[0]->dta_sfida;

        return $obj;
    }


}

?>