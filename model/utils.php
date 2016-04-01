<?php
// $Id: utils.php,v 8bf25770b288 2016/03/06 14:23:34 makhtar $
// Shared utility functions

function Logit($msg)
{
    if (DEBUG_ON) {
        $msg = print_r($msg, true);
        error_log(date('Ymd H:i:s').' '.$msg.PHP_EOL, 3, LOG_FILE);
    }
}

function LogEcho($msg)
{
    echo "<p class='alert-info'>$msg</p>";
    Logit($msg);
}
