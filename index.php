<?php

require_once('vendor/autoload.php');

use DOGECModule\DOGECModule;

$dogec = new DOGECModule();

if (!array_key_exists("address", $_GET) and array_key_exists("rate", $_GET)) {
    if (!isset($_GET['rate']) or $_GET['rate'] == '') {
        echo json_encode([
            "result" => "",
            "error" => "Rate has not been passed"
        ]);
        return;
    }
    $currency = strtolower($_GET['rate']);
    $price = $dogec->getPrice($currency);

    echo json_encode([
        "result" => $price,
        "error" => ""
    ]);
}
else if (!array_key_exists("rate", $_GET) and array_key_exists("address", $_GET)) {
    if (!isset($_GET['address']) or $_GET['address'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Address has not been passed :("
        ]);
        return;
    }
    else if (!isset($_GET['amount']) or $_GET['amount'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Amount value has not been passed :("
        ]);
        return;
    }
    else if (!isset($_GET['otime']) or $_GET['otime'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Timestamp Value has not been passed :("
        ]);
        return;
    }
    else if (!isset($_GET['tx']) or $_GET['tx'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Transaction has not been passed :("
        ]);
        return;
    }
    else if (!isset($_GET['conf']) or $_GET['conf'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Min Confirmations has not been passed :("
        ]);
        return;
    }
    else if (!isset($_GET['mtime']) or $_GET['mtime'] == '') {
        echo json_encode([
            "status" => "invalid",
            "error" => "Max time not has not been passed :("
        ]);
        return;
    }

    $address = $_GET['address'];
    $amount = $_GET['amount'];
    $timestamp = $_GET['otime'];
    $txid = $_GET['tx'];
    $mconf = $_GET['conf'];
    $max_time = $_GET['mtime'];
    $current_timestamp = time();

    if ($current_timestamp > $timestamp + ($max_time * 60)) {
        echo json_encode([
            "status" => "expired",
        ]);
        return;
    }

    $response = $dogec->existsTransaction($address, $amount, $timestamp);
    if (!is_array($response)) {
        echo json_encode([
            "status" => "failed"
        ]);
        return;
    }
    else if ($response['exists']) {
        $trasaction = $response['txid'];
        $confirmations = $dogec->checkConfirmations($trasaction);

        if ($confirmations >= $mconf) {
            echo json_encode([
                "status" => "confirmed",
                "message" => "Transaction successfully confirmed.",
                "transaction_id" => $trasaction,
                "confirmations" => $confirmations
            ]);
            return;
        }
        else {
            echo json_encode([
                "status" => "detected",
                "message" => "Transaction detected. Waiting for confirmations - "
                . $confirmations . "/" . $mconf,
                "transaction_id" => $trasaction,
                "confirmations" => $confirmations
            ]);
            return;
        }
    }
    else {
        echo json_encode([
            "status" => "waiting",
            "message" => "Waiting for payment..."
        ]);
        return;
    }
}
else {
    echo json_encode([
        "result" => "",
        "error" => "Invalid Request"
    ]);
}
?>