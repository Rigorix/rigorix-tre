<h3>Logs</h3>

<div class="row">
    <div class="col-2">
        <div class="list-group">
            <?php
            $files = array();
            $handle = opendir('../log/');
            while (false !== ($file = readdir($handle))):
                if ( $file != ".." && $file != "." && $file != ".svn" ):
                    $files[] = $file;
                endif;
            endwhile;
            rsort($files);
            foreach ( $files as $file) {
                echo '<a class="list-group-item ' . ($_REQUEST["logfile"] == $file ? "active" : ""). '" href="?logfile='.$file.'">' . $file . '</a>';
            }
            ?>
        </div>
    </div>
    <div class="col-10">
        <?php
        if ( isset ($_REQUEST["logfile"])):
            $log_content = file_get_contents("../log/" . $_REQUEST["logfile"]);
            echo "<pre>" . $log_content . "</pre>";
        endif;
        ?>

    </div>

</div>

