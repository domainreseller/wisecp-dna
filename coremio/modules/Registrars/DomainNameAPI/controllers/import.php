<?php

if (!defined("CORE_FOLDER")) {
    die();
}
/** @var $module DomainNameAPI */

$lang = $module->lang;
$data = Filter::POST("data");

if (!$data || !is_array($data)) {
    return false;
}

$results = $module->import_domain($data);

if (!empty($results)) {
    // Build "domain — reason" pairs so the failure type is visible per domain
    // rather than surfacing only the generic import-failed text.
    $details = [];
    foreach ($results as $failedDomain => $reason) {
        $details[] = $failedDomain . ' — ' . $reason;
    }

    die(Utility::jencode([
        'status'  => "error",
        'message' => $lang["error9"] . ': ' . implode(' | ', $details),
        'data'    => $results,
    ]));
}


echo Utility::jencode([
    'status'  => "successful",
    'message' => $lang["success3"],
]);
