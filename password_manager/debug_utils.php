<?php
function logDebug($message, $data = null) {
    $log = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $log .= " - Data: " . print_r($data, true);
    }
    error_log($log);
}

function logPasswordVerification($email, $inputHash, $storedHash) {
    logDebug("Password verification attempt", array(
        'email' => $email,
        'input_hash_length' => strlen($inputHash),
        'stored_hash_length' => strlen($storedHash),
        'match' => $inputHash === $storedHash
    ));
}
?>