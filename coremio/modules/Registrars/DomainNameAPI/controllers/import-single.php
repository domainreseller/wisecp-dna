<?php

if (!defined("CORE_FOLDER")) {
    die();
}
/** @var $module DomainNameAPI */


sleep(1);

$lang    = $module->lang;
$domain  = Filter::POST("domain");
$user_id = Filter::POST("user_id");

if ($user_id < 1 || strlen($domain) < 3) {
    return false;
}

$resp = $module->import_domain([$domain => ['user_id' => $user_id]]);

$hasError = !empty($resp);

echo Utility::jencode([
    'status'  => $hasError ? "error" : "successful",
    // On failure, include the real reason returned by import_domain instead of
    // only the generic error text, so the user sees the actual error type.
    'message' => $hasError ? $lang["error9"] . ': ' . implode(' | ', (array)$resp) : $lang["success3"],
    'data'    => $resp
]);

