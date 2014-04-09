<?php

Flight::set("env", json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env')));

Flight::set("realtime_members_expire_time", 60*2);
Flight::set("realtime_dead_sfida_time", 60*5);
Flight::set("realtime_time_for_single_play", 15);