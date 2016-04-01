<?php
/* $Id: contact.php,v 81f3011898c8 2016/03/06 19:09:43 makhtar $
 * Makhtar Diouf 
 * Input/Edit Contact info
 * Fill in retrieved info ($contact[]) if updating
 */

$isUpdate = (empty($contact) ? false : true);
$updateStr = ($isUpdate ? '&update=true&id=' . $contact['Id'] : '');
?>

<!-- Could make jquery ajax call for posting -->

<form class="form-horizontal" role="form" method="post" 
      action="index.php?action=save<?php echo $updateStr; ?>">

    <div class="form-group">
        <label class="control-label col-md-3" for="name">Name:</label>
        <div class="col-md-9">
            <input type="text" maxlength="20" class="form-control" name="name" required="true" 
                   placeholder="Diouf" value="<?php echo($isUpdate ? $contact['name'] : ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3" for="first_name">First Name:</label>
        <div class="col-md-9">          
            <input type="text" maxlength="20" class="form-control"  name="first_name" required="true" 
                   placeholder="Makhtar" value="<?php echo($isUpdate ? $contact['first_name'] : ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3" for="street">Street:</label>
        <div class="col-md-9">          
            <input type="text" maxlength="40" class="form-control"  name="street" required="true" 
                   placeholder="Boulevard de la republique" value="<?php echo($isUpdate ? $contact['street'] : ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3" for="zip">Zip code:</label>
        <div class="col-md-9">          
            <input type="number" maxlength="8" class="form-control"  name="zip" 
                   value="<?php echo($isUpdate ? $contact['zip_code'] : ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3" for="city">City:</label>
        <div class="col-md-9">               
            <select class="form-control" name="city">
                <?php
                $c = new Contact();
                $stm = $c->GetCities();
                if ($stm) {
                    while ($row = $stm->fetch()) {
                        echo '<option ' . ($row['city'] === $contact['city'] ?
                                "selected='true'" : '') . '>' . utf8_encode($row['city']) . '</option>';
                    }
                }
                ?>                                    
            </select>
        </div>       
    </div>

    <!-- Lists this contact's groups -->
    <div class="form-group"  id="groupList">
        <label class="control-label col-md-3" for="groups">Groups:</label>
        <div class="col-md-6">     
            <input type="text" class="form-control" name="groups" id="groups" 
                  value="<?php echo $c->GetGroups($contact['Id']); ?>">     
        </div>           
    </div>

    <div class="form-group" hidden="true">
        <!-- to be filled by jqapp.js -->
        <input type="text" class="form-control" name="groupIds" id="groupIds" value="">    
    </div>

    <div class="form-group">        
        <div class="col-md-9 col-md-offset-3">
            <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
            <?php $inContactView = true; ?>
            <button type="button" class="btn btn-default" id="addGroupBt"
                    data-toggle="modal" data-target="#groupsPopup"  
                    onclick="//loadGroupPop('views/groups_list.php');">Add to Group</button>

            <?php if ($isUpdate) { ?>
                <a type="button" id="deleteBt" class="btn btn-default" 
                   href="index.php?action=delete&id=<?php echo $contact['Id']; ?>">Delete</a>   
               <?php } ?>
        </div>
    </div>
</form>

<div class="col-md-9 col-md-offset-3">
    <?php echo $c->errorMsg; ?>
</div>

<!-- Load lists of groups in a popup. Will be filled by jQuery on-demand -->
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

</div>