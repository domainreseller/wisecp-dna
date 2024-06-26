<?php

if (!defined("CORE_FOLDER")) {
    die();
}

sleep(1);



$lang = $module->lang;
$domain = Filter::POST("domain");
$user_id = Filter::POST("user_id");

if ($user_id<1 && strlen($domain)<3) {
    return false;
}

$resp = $module->import_domain([$domain=>['user_id'=>$user_id]]);


echo Utility::jencode([
    'status'  => "successful",
    'message' => $lang["success3"],
    'data' => $resp
]);

