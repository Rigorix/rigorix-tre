<?php
class dm_rewards extends dm_generic_mysql {

	function dm_rewards($db_conn, $db_name, $debug=0) {
	    $this->dm_generic_mysql($db_conn, $db_name, $debug);
	}

	function getRewards () {
		return $this->getArrayObjectQueryCustom ("select * from rewards where active = 1 order by tipo");
	}

	function getPuntiRewards () {
		return $this->getArrayObjectQueryCustom ("select * from rewards where tipo = 'punto' and active = 1 order by tipo");
	}

	function getBadgeRewards () {
		return $this->getArrayObjectQueryCustom ("select * from rewards where tipo = 'badge' and active = 1");
	}

	function getBadgeRewardsIdsArray () {
		$ret = array();
		$rewards = $this->getArrayObjectQueryCustom ("select * from rewards where tipo = 'badge' and active = 1");
		foreach ( $rewards as $reward ) {
			array_push( $ret, $reward->id_reward );
		}
		return $ret;
	}

	function getUnotifiedBadgeByIdUtente ( $id_utente ) {
		return $this->getArrayObjectQueryCustom ("SELECT * FROM `sfide_rewards`, rewards where sfide_rewards.id_utente = $id_utente and sfide_rewards.notifica = 0 and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
	}

	function getRewardsByIdUtente ( $id_utente ) {
		return $this->getArrayObjectQueryCustom ("select * from sfide_rewards where id_utente = $id_utente");
	}

	function getRewardsObjectByIdUtente ( $id_utente ) {
		$ret = new stdClass ();
		$ret->punti = array();
		$ret->badges = array();
		$userRewards = $this->getArrayObjectQueryCustom ("select * from sfide_rewards where id_utente = $id_utente");
		foreach ($userRewards as $userReward ) {
			$rewardObject = $this->getRewardById($userReward->id_reward );
			if ( $rewardObject->tipo == "punto" )
				array_push($ret->punti, $rewardObject);
			else
				array_push($ret->badges, $rewardObject);
		}
		return $ret;
	}

	function getCoppeUtente ( $id_utente ) {
		$ret = array();
		$badgeRewardsIds = $this->getBadgeRewardsIdsArray ();
		$rewards = $this->getRewardsByIdUtente ( $id_utente );
		foreach ( $rewards as $reward ) {
			if ( in_array($reward->id_reward, $badgeRewardsIds))
				array_push($ret, $reward);
		}
		return $ret;
	}

	function rewardExists ( $id_sfida, $id_reward, $id_utente ) {
		$r = $this->getSingleObjectQueryCustom ("select count(*) as tot from sfide_rewards where id_reward = '$id_reward' and id_sfida = '$id_sfida' and id_utente = $id_utente");
		return ( $r->tot == 1 ) ? true : false;
	}

	function getRewardById ( $id_reward ) {
		return $this->getSingleObjectQueryCustom ("select * from rewards where active = 1 and id_reward = $id_reward");
	}

	function getRewardByKeyId ( $key_id ) {
		return $this->getSingleObjectQueryCustom ("select * from rewards where active = 1 and key_id = $key_id");
	}

	function applyReward ( $sfida, $userm, $reward ) {
		$insert = array (
			"indb_id_utente" => $userm->id_utente,
			"indb_id_reward" => $reward->id_reward,
			"indb_id_sfida" => $sfida->id_sfida
		);
		$insert_indb = $this->makeInDbObject($insert);
		$id_inserted = $this->insertObject( "sfide_rewards", $insert_indb );
		return $id_inserted;
	}

	function getUserRewardByIdSfida ( $id_utente, $id_sfida ) {

		return $this->getArrayObjectQueryCustom ( "select * from sfide_rewards, rewards where sfide_rewards.id_utente = $id_utente and sfide_rewards.id_sfida = $id_sfida and rewards.id_reward = sfide_rewards.id_reward order by rewards.tipo DESC" );
	}

	function getRewardPointsByIdSfida ( $id_utente, $id_sfida ) {
		$tot_punti = 0;
        $reward_ids = array();
		$rewards = $this->getArrayObjectQueryCustom ("select id_reward from sfide_rewards where id_utente = $id_utente and id_sfida = $id_sfida");
		if ( count ( $rewards) > 0 ) {
			foreach ( $rewards as $reward ) {
                $reward_object = $this->getSingleObjectQueryCustom ("select score, id_reward from rewards where active = 1 and id_reward = " . $reward->id_reward);
				$tot_punti += $reward_object->score;
                if ( $reward_object->score != 0 )
                    array_push($reward_ids, $reward->id_reward);
			}
		}
		return array( "tot_punti" => $tot_punti, "ids" => $reward_ids);
	}

	function getRewardByUser ( $id_utente ) {
		$rewards = $this->getArrayObjectQueryCustom ("select * from sfide_rewards where id_utente = $id_utente");
		return $rewards;
	}

}
?>
