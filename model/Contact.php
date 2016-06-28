<?php

/* $Id: Contact.php,v f7eee0b7a33a 2016/03/06 17:31:05 makhtar $
 * Makhtar Diouf
 * Demo address book app 
 */
require_once 'DbOp.php';

/**
 * Object Model for Contact records, as in ORM frameworks.
 *
 * @author makhtar
 */
class Contact {

    protected $id;
    protected $name;
    protected $first_name;
    protected $street;
    protected $city;     // only in the web form
    protected $city_id;
    protected $zip_code;
    protected $group_ids; // Lists groups to which the Contact belong
    protected $db;
    public $errorMsg;

    public function Contact() {
        $this->db = new DbOp();
        $this->city = 'None';
        $this->group_ids = '';
        $this->errorMsg = '';
    }

    /**
     * Persist a new Contact to the DB.
     * 
     * @param type $update: UPDATE the record if true
     *
     * @return false if query fails
     */
    public function Persist($update = false, $id = 0) {
        try {
            // Partial server-side data validation
            if (empty($this->name)) {
                $this->SetError('The contact name should not be empty');

                return false;
            }
            if (empty($this->zip_code)) {
                $this->zip_code = 0;
            }

            /*
             * Use prepared statements to escape queries
             * Could build a dynamic query
             */
            $values = array('name' => $this->name,
                'first_name' => $this->first_name,
                'street' => $this->street,
                'zip_code' => $this->zip_code,
                'city' => utf8_decode($this->city),);

            if (!$update) {
                $qHead = 'INSERT INTO ';
                $qTail = '';
            } else {
                $qHead = 'UPDATE ';
                $qTail = ' WHERE Id=:Id ';
                $values['Id'] = $id;
            }

            $query = $qHead . TB_CONTACT . ' SET name = :name, first_name = :first_name,' .
                    ' street = :street, zip_code = :zip_code,' .
                    ' city_id = (SELECT Id FROM ' . TB_CITY .
                    ' WHERE city = :city) ' . $qTail;

            $this->db->PrepExecute($query, $values);

            // Handle this contact's groups
            $groupIds = split(',', $this->group_ids);
            $gIds = array_unique($groupIds);

            foreach ($gIds as $k => $gId) {
                if (empty($gId)) {
                    continue;
                }
                $query = 'INSERT INTO ' . TB_CT_GROUPS;
                if (!is_numeric($id)) {
                    // newly inserted contact
                    $query .= ' SET contact_id=LAST_INSERT_ID(), group_id=:gid';
                    $this->db->PrepExecute($query, array('gid' => $gId));
                } else {
                    // updating
                    $query .= ' SET contact_id=:cid, group_id=:gid';
                    $this->db->PrepExecute($query, array('cid' => $id, 'gid' => $gId));
                }
            }
            return true;
        } catch (PDOException $e) {
            LogEcho('DB query error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @assert($id) > 0
     * Return a single contact row
     *
     * @param type $id Contact Id
     *
     * @return type array
     */
    public function GetContact($id, $getClass = false) {
        $query = 'SELECT T1.Id, ' .
                ' name, first_name, street, ' .
                ' zip_code, T2.city ' .
                ' FROM ' . TB_CONTACT . ' AS T1 INNER JOIN ' . TB_CITY . ' AS T2 ' .
                ' ON T1.city_id = T2.Id ' .
                ' WHERE T1.Id=:Id ';

        if ($getClass) {
            $stm = $this->db->PrepExecute($query, array('Id' => $id), PDO::FETCH_CLASS, 'Contact');
        } else {
            $stm = $this->db->PrepExecute($query, array('Id' => $id));
        }

        if ($stm) {
            $row = $stm->fetch();
            Logit($row);
            return $row;
        }

        return array();
    }

    /**
     * Retrieve registered contacts and their city.
     *
     * @global $TB_CONTACT
     *
     * @return type array, Last 100 contacts by default
     */
    public function GetContacts($order = 'DESC', $limit = 'LIMIT 100') {
        $stm = $this->db->PrepExecute('SELECT T1.Id, ' .
                ' name, first_name, street, ' .
                ' zip_code, T2.city ' .
                ' FROM ' . TB_CONTACT . ' AS T1 INNER JOIN ' . TB_CITY . ' AS T2 ' .
                ' ON T1.city_id = T2.Id ' .
                " ORDER BY Id $order $limit ");

        // $stm->setAttribute(PDO::FETCH_CLASS, 'Contact');
        return $stm;
    }

    /**
     * Groups to which this contact is added.
     *
     * @return string list of groups
     */
    public function GetGroups($id) {
        if (!is_numeric($id)) {
            return "";
        }

        $stm = $this->db->PrepExecute('SELECT T1.contact_id, ' .
                ' T1.group_id, T2.Id, T2.name ' .
                ' FROM ' . TB_CT_GROUPS . ' AS T1 ' .
                ' INNER JOIN ' . TB_GROUPS . ' AS T2 ' .
                ' ON T1.group_id = T2.Id ' .
                ' WHERE T1.contact_id=:cid', array('cid' => $id));

        $groupStr = "";
        if ($stm) {
            $groups = array();
            for ($i = 0; $i < $stm->rowCount(); $i++) {
                $row = $stm->fetch();
                $groups[$i] = $row['name']; // Group name
            }
            // Remove duplicates
            $groups = array_unique($groups);
            foreach ($groups as $k => $name) {
                $groupStr .= "$name, ";
            }
        }

        return $groupStr;
    }

    /**
     * Delete one contact.
     *
     * @param type $id
     */
    public function Delete($id) {
        if (!is_numeric($id)) {
            LogEcho("Invalid contact Id $id");
            return false;
        }
        $query = 'DELETE FROM ' . TB_CONTACT . " WHERE Id='" . $id . "'";
        $ret = $this->db->Query($query);

        if (!$ret) {
            return false;
        }

        LogEcho("Successfully deleted Contact with Id $id");
        return true;
    }

    /**
     * List of cities for the contact input/edit form.
     * 
     * @return array
     */
    public function GetCities() {
        $rows = $this->db->Query('SELECT city FROM ' . TB_CITY);
        if ($rows) {
            return $rows;
        }

        return false;
    }

    /**
     * Export contacts to xml.
     *
     * @return array(rows count, filename)
     */
    public function ExportAllContacts() {
        try {
            Logit('Exporting contacts...');
            $ret = $this->GetContacts('ASC', ' ');
            
            if (!$ret || $ret->rowCount() < 1) {
                LogEcho('No contacts to export');
                return array();
            }

            // Temporary filename
            $tmp = tempnam('./', 'addbook' . date('Ymd') . '-');
            Logit("Temporary file: $tmp");

            $xml = new XMLWriter();
            $xml->openURI($tmp);
            $xml->startDocument();
            $xml->startElement('contacts');
            $xml->startElement('date');
            $xml->text(date('Y-m-d H:i:s'));
            $xml->endElement();

            $bytes = '';
            $ret->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $ret->fetch()) {
                $xml->startElement('contact');
                foreach ($row as $key => $value) {
                    $xml->startElement($key);  // Id, name...
                    $xml->text(utf8_encode($value));
                    $xml->endElement();
                }
                $xml->endElement(); // </contact>   
                $bytes += $xml->flush();
                Logit("$bytes bytes written");
            }
            $xml->endElement();   // </contacts>     
            $xml->endDocument();

            if (!file_exists(EXPORT_DIR)) {
                mkdir(EXPORT_DIR, 0775);
            }
            chmod($tmp, 0664);
            $filename = EXPORT_DIR . '/' . basename($tmp) . '.xml';
            $done = rename($tmp, $filename);
            if (!$done) {
                LogEcho("Could not rename the xml file to $filename." . PHP_EOL .
                        'Please verify the access permissions of ' . EXPORT_DIR);
            }

            return array('rowcount' => $ret->rowCount(), 'filename' => $filename);
            
        } catch (Exception $ex) {
            LogEcho('Export error: ' . $ex->getMessage());
            return array();
        }
        unset($xml);
    }

    public function SetError($msg) {
        $this->errorMsg = "<p class='alert-danger'>$msg</p>";
        Logit($msg);
    }

    //**** Auto-generated Get/Setters 

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFirstName() {
        return $this->first_name;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getCity() {
        return $this->city;
    }

    public function getZip() {
        return $this->zip_code;
    }

    /**
     * @return type array of Ids
     */
    public function getGroupIds() {
        return $this->group_ids;
    }

    /**
     * @param string $groupIds: String of group names to add to the array of Group Ids
     *
     * @return \Contact
     */
    public function setGroups($groupIds, $id) {
        if (empty($groupIds)) {
            Logit("Can not add contact to invalid group $groupIds");
            return false;
        }
        $this->group_ids = $groupIds;
        /*
          $groups = split(',', $groupIds);
          $groupIds = array_unique($groups);

          foreach ($groupIds as $key => $groupId) {
          if (empty($groupId)) {
          continue;
          }

          $query = 'INSERT INTO ' . TB_CT_GROUPS .
          ' SET contact_id=:cid, ' .
          'group_id=:gid';

          $stm = $this->db->PrepExecute($query, array('cid' => $id,
          'gid' => $groupId,));
          }
         */
        return true;
    }

    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;

        return $this;
    }

    public function setStreet($street) {
        $this->street = $street;

        return $this;
    }

    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    public function setZip($zip_code) {
        $this->zip_code = $zip_code;

        return $this;
    }

}
