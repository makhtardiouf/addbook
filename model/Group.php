<?php

/* $Id: Group.php,v dfb31dc4875e 2016/03/07 18:13:14 makhtar $
* Makhtar Diouf
* Demo address book app 
* Object Model for Group records
* Create 1:many group(s) of contacts, inherit between them.
*/
require_once 'DbOp.php';

class Group {

    protected $id;
    protected $name;
    protected $parentGroup;
    protected $parentId;
    protected $db;
    protected $fetchMode;

    public function __construct() {
        $this->db = new DbOp();
        $this->parentId = 0;
        $this->name = 'default';
        $this->fetchMode = PDO::FETCH_ASSOC;
    }

    /**
    * Save a new Group to the DB.
    *
    * @param type $update: UPDATE the record if true
    *
    * @return false if it fails
    */
    public function Persist($update = false, $id = 0) {
        try {
            // Partial server-side data validation
            if (empty($this->name)) {
                LogEcho('The group name should not be empty');

                return false;
            }
            if (empty($this->parentId)) {
                $this->parentId = 1;
            }

            // Use prepared statements to escape queries

            if (!$update) {
                $qHead = 'INSERT INTO ';
                $qTail = '';
                $values = array('name' => $this->name);
            } else {
                $qHead = 'UPDATE ';
                $qTail = 'WHERE Id=:Id';
                $values = array('name' => $this->name, 'Id' => $id);
            }

            $con = $this->db->GetConnection();
            $con->beginTransaction();

            $query = $qHead . TB_GROUPS . ' SET name = :name ' . $qTail;
            $stm = $con->prepare($query);
            $ret = $stm->execute($values);
            $con->commit();

            //** Cannot concurrently INSERT/UPDATE & SELECT in the same table,
            // so we get parent_id separately

            $con->beginTransaction();
            $query = 'SELECT Id FROM ' . TB_GROUPS . ' WHERE name =:parent_group ';
            $this->parentId = 0; // no parent

            $stm = $this->db->PrepExecute($query, array('parent_group' => $this->parentGroup));

            if ($stm && ($stm->rowCount() > 0)) {
                $row = $stm->fetch();
                // A group can not be it's own parent
                if ($this->parentId !== $row['Id']) {
                    $this->parentId = $row['Id'];
                }
            }
            $query = 'UPDATE ' . TB_GROUPS . ' SET parent_id = :pid' .
                    ' WHERE name = :name';

            $stm = $con->prepare($query);
            $stm->execute(array('name' => $this->name, 'pid' => $this->parentId));

            $ret = $con->commit();
            if (!$ret) {
                $con->rollBack();
                LogEcho('Error: failed setting parent of group ' . $this->name);
                return false;
            }

            return true;
        } catch (PDOException $e) {
            LogEcho('Could not save group information: ' .
                    $this->name . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
    * Retrieve one group.
    *
    * @return type array
    */
    public function GetGroup($id) {
        if (!is_numeric($id)) {
            LogEcho("Can not access group with invalid Id $id");
            return false;
        }
        $stm = $this->db->PrepExecute('SELECT Id, name, parent_id FROM ' .
                TB_GROUPS . ' WHERE Id=:gid', array('gid' => $id));

        if ($stm) {
            return $stm->fetch();
        }
        return false;
    }

    /**
    * Retrieve registered groups.
    *
    * @return type array, Last 100 groups by default
    */
    public function GetGroups($order = 'ASC', $limit = 'LIMIT 100') {
        $query = 'SELECT Id, name, parent_id FROM ' . TB_GROUPS .
                " ORDER BY Id $order $limit";

        if ($this->fetchMode === PDO::FETCH_CLASS) {
            $stm = $this->db->PrepExecute($query, array(), $this->fetchMode, 'Group');
        } else {
            $stm = $this->db->PrepExecute($query, array());
        }

        return $stm;
    }

    /**
    * Get Contacts that belong to this Group and its parents.
    *
    * @return array of PDOStatement
    */
    public function GetContacts($gid) {
        Logit("-------- Getting contacts of group id $gid ------");
        if (!is_numeric($gid)) {
            LogEcho("Can not get contacts of invalid group id $gid");

            return false;
        }
        
        if ($gid == 1) {
                // GroupD hold all contacts
                $query = "SELECT * FROM " . TB_CONTACT;
                $stms[] = $this->db->PrepExecute($query, array('gid' => $gid));
                return $stms;  
            } else {
                $query = 'SELECT T1.contact_id, T1.group_id, ' .
                ' T2.Id, T2.name, T2.first_name ' .
                ' FROM ' . TB_CT_GROUPS . ' AS T1 ' .
                ' JOIN ' . TB_CONTACT . ' AS T2 ' .
                ' ON T1.contact_id = T2.Id ' .
                ' WHERE T1.group_id=:gid';
            }

        $stms[] = $this->db->PrepExecute($query, array('gid' => $gid));

        $probParent = 'SELECT T3.parent_id FROM ' . TB_GROUPS . ' AS T3' .
                ' WHERE T3.Id=:gid';

        // Recurse with parent groups and their contacts
        $refGroupId = $gid;
        do {
            $stm = $this->db->PrepExecute($probParent, array('gid' => $gid));
            if (!$stm || ($stm->rowCount() == 0)) {
                break;
            } 
            $row = $stm->fetch();
            $pid = $row['parent_id']; // parent    
            $stm2 = $this->db->PrepExecute($query, array('gid' => $pid));
            if (!$stm2 || ($stm2->rowCount() == 0)) {
                break;
            }
            // Append Contacts from the $pid parent group
            $stms[] = $stm2;
            $gid = $pid;
            // Stop when cycling back to the group requested initially 
            if ($gid == $refGroupId)
                break;
        } while ($stm);

        return $stms;
    }

    /**
    * Delete one group.
    *
    * @assert($id > 0)
    *
    * @param int $id
    */
    public function Delete($id) {
        if (!is_numeric($id)) {
            LogEcho("Invalid group Id $id");

            return false;
        }
        $query = 'DELETE FROM ' . TB_GROUPS . " WHERE Id='" . $id . "'";
        $ret = $this->db->Query($query);

        if (!$ret) {
            return false;
        }

        LogEcho("Successfully deleted group with Id $id");
        return true;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function getParentGroup() {
        return $this->parentGroup;
    }

    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;

        return $this;
    }

    public function setParentGroup($parentGroup) {
        $this->parentGroup = $parentGroup;

        return $this;
    }

    public function getFetchMode() {
        return $this->fetchMode;
    }

    public function setFetchMode($fetchMode) {
        $this->fetchMode = $fetchMode;

        return $this;
    }

}
