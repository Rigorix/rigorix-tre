<h3>Tests</h3>

<div class="row">
    <div class="col-2">
        <div class="list-group">
            <?php
            $files = array();
            $handle = opendir('../tests/');
            while (false !== ($file = readdir($handle))):
                if ( $file != ".." && $file != "." && $file != ".svn" ):
                    $files[] = $file;
                endif;
            endwhile;
            rsort($files);
            foreach ( $files as $file) {
                $filename = str_replace("_", " ", $file);
                $filename = str_replace(".php", "", $filename);
                echo '<a class="list-group-item ' . ($_REQUEST["testfile"] == $file ? "active" : ""). '" href="?testfile='.$file.'">' . $filename . '</a>';
            }
            ?>
        </div>
    </div>
    <div class="col-10">
    	<iframe src="../tests/<?php echo $_REQUEST["testfile"]; ?>" width="100%" height="500px" frameborder="no"></iframe>
      <?php
      var_dump ( $_REQUEST["testfile"] );
      ?>
    </div>

</div>

