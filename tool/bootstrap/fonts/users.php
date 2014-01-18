<h3>Users</h3>

<div class="row">
    <div class="col-12">
        <table class="table table-striped table-list table-bordered">
        <tr>
        		<th>id</th>
        		<th>username</th>
        </tr>
		  <?php
		  $users = $db->getArrayObjectQueryCustom("select * from utente");
		  foreach ( $users as $user ): var_dump($user); ?>
		  
		  		<tr>
					<td><?php echo $user->id_utente; ?></td>
					<td>php echo $user->username; ?></td>		  
		  		</tr>
		  
		  <?php endforeach; ?>
        </table>
    </div>

</div>

