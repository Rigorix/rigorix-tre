<?php
global $user;
?>

<div class="well well-small mtl">
    <div class="row-fluid">
        <div class="span4">
            <img width="100%" class="img-circle" socialid="<?php echo $user->obj->social_uid; ?>" src="<?php echo $user->obj->picture; ?>" />
        </div>
        <div class="span8">
            <h4 class="text-info"><?php echo $user->obj->social_provider; ?> <small>badge</small></h4>
            <h5><?php echo $user->obj->username; ?></h5>
            <h6><?php echo $user->obj->email; ?></h6>
        </div>
    </div>
</div>