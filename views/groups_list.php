<?php
/*
 * Lists groups in the DB, with pagination
 * To be included in Groups page and Contact "Add to Group" popup
 */
//include_once "../model/Group.php";
$g = new Group();
$g->setFetchMode(PDO::FETCH_ASSOC);
$stm = $g->GetGroups();
if (!$stm) {
    echo 'No groups recorded yet<br>';
    return;
}

$maxRows = PAGINATION_ROWS;
$rows = $stm->rowCount();
?>
<h5><?php echo "$rows groups"; ?></h5>

<table class="table table-striped">
    <th></th><th>Id</th><th>Name</th><th>Parent Group</th><th>Contacts</th>
    <?php
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($page) {
        // Ignore rows depending on the page        
        for ($j = 0; $j < ($page - 1) * $maxRows; ++$j) {
            $stm->fetch();
        }
    }

    $i = 0;
    while ($row = $stm->fetch()) {
        $id = $row['Id'];
        $ctCount = 0;
        $stms = $g->GetContacts($id);
        foreach ($stms as $key => $stm2) {
            // Number of Contacts for each group
            $ctCount += $stm2->rowCount();
        }

        $gName = $row['name'];
        echo "<tr><td><input type='checkbox' id='$id' value='$gName'></td>" .
        "<td>$id</td>" .
        "<td><a href='index.php?action=group_edit&id=$id'>" .
        "$gName</a></td><td>" . $row['parent_id'] . '</td>' .
        "<td><a href=''>$ctCount</a></td></tr>";
        ++$i;
        if ($i == $maxRows) {
            $rows->closeCursor();
            break;
        }
    }
    ?>
</table>

<?php
if (!$inContactView) {
?>
<div class="col-md-6 col-md-offset-1">    
    <input type="submit" id="deleteGroupBt" class="btn btn-default" value='Delete'>
</div>
<?php } ?>

<div class="col-md-4">   
    <ul class="pagination">
        <?php
        $p = 1;

        for ($i = 0; $i <= $rows; ++$i) {
            if (($i % $maxRows) == 0) {
                $class = '';
                if ($page && ($p == $page)) {
                    $class = "class='active'";
                }
                echo " <li $class><a href='index.php?action=group_list&page=$p'>$p</a></li>";
                ++$p;
            }
        }
        ?>
    </ul>
</div>
