<?php
$start_page = $_REQUEST["start_page"] | 0;
$items_per_page = 10;
$users = json_decode( get_from_service( "?getTable=utente&limit=$start_page,$items_per_page" ));
$users_count = json_decode( get_from_service( "?getTable=utente&getFields=count(*)" ));
$users_count = $users_count[0]->{"count(*)"};
?>

<div class="row" style="margin-top: -65px; margin-bottom: -10px;">
    <div class="col-12 text-center">
        <ul class="pagination">
            <li class="<?=$start_page == 0 ? "disabled" : ""?>"><a href="?start_page=<?=$start_page-$items_per_page?>">&laquo;</a></li>
            <? for ( $i=1; $i<= ceil($users_count / $items_per_page); $i++ ): ?>
                <li class="<?=$start_page == $items_per_page * ($i-1) ? "active" : "" ?>"><a href="?start_page=<?=$items_per_page * ($i-1)?>"><?=$i?></a></li>
            <? endfor; ?>
            <li class="<?=$start_page+$items_per_page > $users_count ? "disabled" : ""?>"><a href="?start_page=<?=$start_page+$items_per_page?>">&raquo;</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <table class="table table-striped table-list table-bordered">
        <tr>
        		<th>id</th>
        		<th>stato</th>
        		<th>username</th>
        		<th>email</th>
        		<th>social</th>
				<th>actions</th>
        </tr>
		  <?php
		  foreach ( $users as $user ): ?>
		  
		  		<tr>
					<td><?php echo $user->id_utente; ?></td>
					<td>
						<?php
						switch ( $user->attivo ) {
							case 0:
								echo '<span class="text-danger glyphicon glyphicon-thumbs-down"></span>';
								break;
							case 1:
								echo '<span class="text-success glyphicon glyphicon-thumbs-up"></span>';
								break;
						}
						?>
					<td><img src="<?php echo $user->picture; ?>" width="20" class="img-circle"/> <?php echo $user->username; ?> <span class="badge"><?php echo $user->punteggio_totale; ?></span></td>		
					<td><?php echo $user->email; ?></td>					
					<td><a href="<?php echo $user->social_url; ?>" class="badge"><?php echo str_replace("__DELETED__","", $user->social_provider); ?></a></td>
					<td>
						<div class="btn-group">
						  <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
						    Actions <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href="#">View online profile</a></li>
						    <li><a href="#">View sfide</a></li>
						    <li class="divider"></li>
						    <li><a href="#" class="text-danger">Delete!</a></li>
						  </ul>
						</div>
					</td>  
		  		</tr>
		  
		  <?php endforeach; ?>
        </table>
    </div>

</div>

