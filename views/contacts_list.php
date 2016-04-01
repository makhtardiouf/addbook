<?php
/* $Id: contacts_list.php,v dfb31dc4875e 2016/03/07 15:13:14 makhtar $
 * Makhtar Diouf 
 * Lists contacts in the DB, with pagination
 */
// When included from a group page
if ($inGroupView) {
    $info = " in this group";
} else {
    $c = new Contact();
    $stms[] = $c->GetContacts();
    $stm = $stms[0];
}

if (!$stms) {
    echo "No contacts registered yet $info<br>";
    return;
}
$nRows = 0;
$maxRows = PAGINATION_ROWS;

// $stms is an array of PDOStatements for each group and its parents
foreach ($stms as $k => $stm) {
    $nRows += $stm->rowCount();
}

echo "<h5>$nRows contacts $info</h5>";
if ($nRows == 0)
    return;

echo '<table class="table table-striped">';
if ($inGroupView)
    echo "<th>Id</th><th>Name</th>";
else
    echo '<th></th><th>Id</th><th>Name</th><th>Address</th>';

foreach ($stms as $k => $stm) {
    $i = 0;
    while ($row = $stm->fetch()) {

        $page = Get('page');
        if ($page) {
            // Ignore rows depending on the page        
            for ($j = 0; $j < ($page - 1) * $maxRows; ++$j) {
                $stm->fetch();
            }
        }

        $id = $row['Id'];
        $link = "<a href='index.php?action=edit&id=$id'> ";
        echo "<tr>";

            if (!$inGroupView)
                echo "<td><input type='checkbox' id='$id'></td>";

            echo "<td>$id</a></td>" .
            '<td>' . $link . $row['name'] . ' ' . $row['first_name'] . '</a></td>';

            if (!$inGroupView) {
                echo '<td>' . $row['street'] . ', ' .
                utf8_encode($row['city']) . ' ' .
                $row['zip_code'] . '</td>';
            }
        echo '</tr>';
        ++$i;
        if ($i == $maxRows) {
            $stm->closeCursor();
            break;
        }
    }
}
echo '</table>';

if (!$inGroupView) {
    ?>
    <div class="col-md-6 col-md-offset-1">    
        <!-- Add selected contacts to selected groups -->       
        <?php $inContactView = true; ?>
        <button type="button" class="btn btn-default" id="addGroupBt"
                data-toggle="modal" data-target="#groupsPopup">Add to Group</button>

        <input type="submit" id="deleteBt" class="btn btn-default" value='Delete'>
    </div>
<?php } ?>

<div class="col-md-4">   
    <ul class="pagination">
        <?php
        $p = 1;
        // Break it down into several pages links
        for ($i = 0; $i <= $nRows; ++$i) {
            if (($i % $maxRows) == 0) {
                $class = '';
                if ($page && ($p == $page)) {
                    $class = "class='active'";
                }
                echo " <li $class><a href='index.php?action=list&page=$p'>$p</a></li>";
                ++$p;
            }
        }
        ?>
    </ul>
</div>

<!-- Load lists of groups in a popup. 
    Could be filled with jQuery on-demand... -->
    
<div id="groupsPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add the contact to selected groups </h5>
            </div>

            <div class="modal-body" id="groupsTable">          
            <?php $inContactView = true; include_once 'views/groups_list.php'; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" 
                        onclick="show('groupIds');" >Close</button>

                <button type="button" class="btn btn-primary" data-dismiss="modal" 
                        onclick="show('groupIds');" id="addGroupBt">Add</button>
            </div>
        </div>
    </div>
</div>
