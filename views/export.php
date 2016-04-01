<?php
/* $Id: export.php,v 9bd529b900cb 2016/03/05 15:50:22 makhtar $
 * Makhtar Diouf 
 * Export contacts list from the DB to an xml file
 */
?>
<div class="jumbotron">
    <p>Export all your contacts to an XML file:</p>
    
    <p>
        <a href="index.php?action=export&proc=true" 
           class="btn btn-primary" role="button">Proceed</a> 
    </p>

    <?php
    // Process only after the button above was clicked
    if (!isset($_REQUEST['proc']) && ($_REQUEST['proc'] != true)) {
        return;
    }

    $c = new Contact();
    $arr = $c->ExportAllContacts();

    if (!empty($arr)) {
        $filename = $arr['filename'];
        echo '<br>'.$arr['rowcount'].' records exported.'.
        '<br>Download XML file ['.sprintf('%.2f', filesize($filename) / 1024).' KB]: '.
        "<a href='$filename' target='_blank'>".basename($filename).'</a>';
        // For later removal
        $_SESSION['exportedfile'] = $filename;
    }
    ?>

</div>
