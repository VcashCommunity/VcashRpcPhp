<?php

// Test module
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;charset=utf-8");

/**
 * Request rpc class and functions
 */
require_once 'VcashRpc.php';

echo "rpc_getinfo\n";
echo json_encode(VcashRpc::rpc_getinfo(), JSON_PRETTY_PRINT);
echo "\n\nrpc_getbalance\n";
echo json_encode(VcashRpc::rpc_getbalance(), JSON_PRETTY_PRINT);
echo "\n\nrpc_getblockcount\n";
echo json_encode(VcashRpc::rpc_getblockcount(), JSON_PRETTY_PRINT);
