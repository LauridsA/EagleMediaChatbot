<?php

namespace App\Helpers;

use App\Http\Controllers\MAndBController;

if (isset($_POST['action'])) {
    $ctr = new MAndBController();
    debug_to_console($_POST['action']);
    $ctr->removeButton($_POST['action']);
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>alert( 'Debug Objects: " . $output . "' );</script>";
}