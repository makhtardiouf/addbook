<?php

/* $Id: index.php,v f7eee0b7a33a 2016/03/06 17:31:05 makhtar $
 * Makhtar Diouf
 * Demo address book app 
 * Entry point that mimics a front controller
 */
require_once "views/header.php";

function __autoload($class) {
    $file = "./model/" . $class . ".php";
    include_once($file);
}

$action = Get("action");

switch ($action) {
    //*** Contacts operations  

    case 'list':
        require_once 'views/contacts_list.php';
        break;

    case 'save':       
        $c = new Contact();
        $c->setName(GetPost('name'));
        $c->setFirstName(GetPost('first_name'));
        $c->setStreet(GetPost('street'));
        $c->setCity(GetPost('city'));       // will be mapped to city_id
        $c->setZip(GetPost('zip'));
        $c->setGroups(GetPost('groupIds'), Get("id"));

        $saved = $c->Persist(Get("update"), Get("id"));
        if ($saved) {
            require_once 'views/contacts_list.php';
        } else
            ShowError("Contact $c->getName() could not be saved");

        break;

    case 'edit':
        $id = Get('id');
        if (!$id) {
            ShowError("Contact Id not specified");
            break;
        }

        $c = new Contact();
        $contact = $c->GetContact($id);
        require_once 'views/contact.php';
        break;

    case 'export':
        require_once 'views/export.php';
        break;

    case 'delete':
        $c = new Contact();
        $done = $c->Delete(Get("id"));
        if ($done) {
            require_once 'views/contacts_list.php';
        }
        break;


    //*** Groups operations

   case 'group_list':
    // Alias for for group_list
    case 'group_new':
        require_once 'views/groups.php';
        break;

    case 'group_save':
        $id = Get('id');
        $g = new Group();
        $g->setName(GetPost("name"));
        $g->setParentGroup(GetPost("parent_group"));
        $g->Persist(Get("update"), Get("id"));

        require_once 'views/groups.php';
        break;


    case 'group_edit':
        $id = Get('id');
        if (!$id) {
            ShowError("Group Id not specified");
            break;
        }
        $g = new Group();
        $group = $g->GetGroup($id);
        require_once 'views/groups.php';
        break;


    case 'group_delete':
        $g = new Group();
        $done = $g->Delete(Get("id"));
        if ($done) {
            require_once 'views/groups_list.php';
        }

        break;


    default :
        require_once 'views/contact.php';
        // Remove file after leaving the export page
        if (file_exists($_SESSION["exportedfile"])) {
            unlink($_SESSION["exportedfile"]);
        }
        break;
}

require_once "views/footer.php";
