<?php
/* $Id: groups.php,v f7eee0b7a33a 2016/03/06 17:31:05 makhtar $
 * Makhtar Diouf 
 * Create groups for contacts, connect them
 */
$isUpdate = (empty($group) ? false : true);
$updateStr = ($isUpdate ? '&update=true&id=' . $group['Id'] : '');

if ((Get('action') === 'group_new') || (Get('action') === 'group_edit')) {
    ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <form class="form-horizontal" role="form" method="post" 
                  action="index.php?action=group_save<?php echo $updateStr; ?>">

                <div class="form-group">
                    <label class="control-label col-md-3" for="group">Group Name:</label>
                    <div class="col-md-9">
                        <input type="text" maxlength="20" class="form-control"  name="name"
                               value="<?php echo($isUpdate ? $group['name'] : ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3" for="parent_group">Parent Group:</label>
                    <div class="col-md-9">  
                        <!-- Dropdown list of Groups -->
                        <select class="form-control" name="parent_group">
                            <option></option>
                            <?php
                            $gr = new Group();
                            $stm = $gr->GetGroups();
                            if ($stm) {
                                while ($g = $stm->fetch()) {
                                    echo '<option ' . ($g['Id'] === $group['parent_id'] ?
                                            "selected='true'" : '') . '>' . utf8_decode($g['name']) . '</option>';
                                }
                            }
                            ?>                
                        </select>
                    </div>
                </div>

                <div class="form-group">        
                    <div class="col-md-9 col-md-offset-3">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div> <!-- row -->
    <hr />

    <div class="row">    
        <div class="col-md-8 col-md-offset-2">
            <?php
            // List contacts of this group
            if ($isUpdate) {
              //  $g = new Group();
                $stms = $gr->GetContacts($id);
                $inGroupView = true;
                require_once 'contacts_list.php';
            }
            ?>
        </div>
    </div> <!-- row -->

    <?php
} else {
// Lists all groups
    include_once 'views/groups_list.php';
}