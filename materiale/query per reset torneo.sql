update tornei_sfide set risultato = '', stato = 0, a_tavolino = 0 where stato > 3;
update tornei set stato = 1, fase = 0, fase_playoff = 0, id_vincitore = 0;
delete from tornei_sfide where tipo_competizione = '1'; 