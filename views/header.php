<?php
/* $Id: header.php,v 88c0940a0921 2016/03/06 19:11:56 makhtar $
 * Makhtar Diouf
 * Demo address book app 
 * Header with links common to all pages
 */
error_reporting(E_ALL & E_NOTICE);
session_start();

// Safer functions to access requests vars
function Get($field)
{
    return filter_input(INPUT_GET, $field, FILTER_SANITIZE_SPECIAL_CHARS);
}

function GetPost($field)
{
    return filter_input(INPUT_POST, $field, FILTER_SANITIZE_SPECIAL_CHARS);
}

function ShowInfo($msg)
{
    echo "<p class='alert-success jumbotron'>$msg</p>";
}

function ShowError($msg)
{
    echo "<p class='alert-danger jumbotron'>$msg</p>";
}
?>
<!DOCTYPE html>
<html>
    <head>       
        <title>Contacts app - Makhtar Diouf</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">   
        <meta name="description" content="" />
        <meta name="author" content="Makhtar Diouf" />
        <link rel="stylesheet" href="res/css/main.css">
        <!-- CSS framework for Responsive Web Design layout -->
        <link rel="stylesheet" href="res/css/bootstrap.min.css">
        <script src="res/js/jquery.min.js"></script>
        <script src="res/js/bootstrap.min.js"></script>
        <script src="res/js/jqapp.js"></script>
    </head>

    <body id="content">
        <div class="container">
            <div class="row center-block">
                <div class="col-md-1"></div>    
                <div class="col-md-10"> 
                    <h3>Address book demo</h3>

                    <!-- Menu bar -->   
                    <div class="row">                
                        <nav class="navbar navbar-inverse" role="navigation">                   
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainnavbar">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span> 
                                </button>     
                            </div>

                            <div class="collapse navbar-collapse" id="mainnavbar" >
                                <ul class="nav navbar-nav" >                                
                                    <li><a href="index.php">New Contact 
                                            <span class="glyphicon glyphicon-floppy-disk"></span></a>
                                    </li> 
                                    <li><a href="#" class="bar"> | </a> </li>
                                    <li><a href="index.php?action=list">List
                                            <span class="glyphicon glyphicon-list-alt"></span></a>
                                    </li> 
                                    <li><a href="#" class="bar"> | </a> </li>
                                    <li><a href="index.php?action=export" >Export
                                            <span class="glyphicon glyphicon-export"></span></a>
                                    </li>                                                                                              
                                </ul>
                            </div>                             
                        </nav>               
                    </div>
                    <!-- End Header -->   


                    <div class="row" style="padding-bottom: 5px;"> 

                        <!-- Left menu -->                                                   
                        <nav class="col-md-2">
                                <ul class="nav nav-pills nav-stacked"  data-spy="affix" data-offset-top="100">
                                    <li class="active"><a href="index.php">Add Contact</a></li>
                                    <li class="btn-toolbar"><a href="index.php?action=list">List Contacts</a></li>                                
                                                                
                                    <li class="active"><a href="index.php?action=group_new">Add Group</a></li>
                                    <li class=""><a href="index.php?action=group_list">List Groups</a></li>                                       
                                
                                    <li class="active"><a href="index.php?action=export">Export</a></li>
                                </ul>
                        </nav>                    

                        <!-- Main body content -->
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-body" id="maincontent">
                                    <div class="alert-info" id="message">            
                                        <?php echo(isset($_SESSION['message']) ? $_SESSION['message'] : ''); ?>
                                    </div>