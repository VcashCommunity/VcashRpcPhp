<?php

require_once 'JsonRpcClient.php';

class VcashRpc {

    // General rpc caller
    private static function call_rpc($payload) {
        try {
            // Use JsonRpcClient library from https://github.com/jenolan/jsonrpcx-php/
            $serverUrl = 'http://127.0.0.1:9195';
            $client = new JsonRpcClient($serverUrl);
            $response = $client->call($payload->method, $payload->params, $payload->id);
            // Decode the response
            $data = json_decode($response, true);
            return $data;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function rpc_getinfo() {
        // getinfo
        $payload = (object) ['id'=> 1, 'method' => 'getinfo', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_getbalance() {
        # getbalance
        $payload = (object) ['id'=> 1, 'method' => 'getbalance', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_getnewaddress() {
        # getnewaddress: Get new vcash address
        $payload = (object) ['id'=> 1, 'method' => 'getnewaddress', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_listtransactions() {
        # listtransactions
        $payload = (object) ['id'=> 1, 'method' => 'listtransactions', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_listreceivedbyaddress() {
        # listreceivedbyaddress 1: received with minimum 1 confirmations
        $confirm_number = 1;
        $payload = (object) ['id'=> 1, 'method' => 'listreceivedbyaddress', 'params' => array($confirm_number)];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_gettransaction($txid) {
        # gettransaction
        $payload = (object) ['id'=> 1, 'method' => 'gettransaction', 'params' => array($txid)];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_getblockcount() {
        # getblockcount
        $payload = (object) ['id'=> 1, 'method' => 'getblockcount', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_getdifficulty() {
        # getdifficulty
        $payload = (object) ['id'=> 1, 'method' => 'getdifficulty', 'params' => array()];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_validateaddress($address) {
        # validateaddress
        $payload = (object) ['id'=> 1, 'method' => 'validateaddress', 'params' => array($address)];
        return VcashRpc::call_rpc($payload);
    }


    public static function rpc_sendtoaddress($address, $amount) {
        // WARNING: USE WITH CAUTION
        # sendtoaddress
        // Do checks: address is valid and amount too.
        $payload = (object) ['id'=> 1, 'method' => 'sendtoaddress', 'params' => array($address, $amount)];
        return VcashRpc::call_rpc($payload);
    }


    public static function check_received($address) {
        // Check if address has received funds from user
        // address is generated by rpc_getnewaddress() (New empty address)
        // Triple check: listreceivedbyaddress, listtransactions, gettransaction
        $amount = 0;
        $user_address = null;
        $status_received = false;
        // Check if $address has received funds in recent transaction
        // parse listreceivedbyaddress look for $address
        $response = VcashRpc::rpc_listreceivedbyaddress();
        foreach ($response['result'] as $received) {
            if ($received['address'] == $address) {
                // Searched address found
                // Do stuff
                $amount = $received['amount'];
            }
        }

        // Parse listtransactions
        if ($amount > 0) {
            // Recheck the transaction and get user address.
            // After the check we will have all needed data (house_address, user_address, bet_amount)
            $response = VcashRpc::rpc_listtransactions();
            foreach ($response['result'] as $trans) {
                if ($trans['address'] == $address) {
                    $txid = $trans['txid'];
                    $txdata = VcashRpc::rpc_gettransaction($txid);
                    $user_address = $txdata['result']['vout'][0]['scriptPubKey']['addresses'][0];
                }
            }
        }

        if ($amount > 0 && !empty($user_address)) {
            $status_received = true;
        }

        $data = array("received"=>$status_received, "house_address"=>$address,
            "user_address"=>$user_address , "amount"=>$amount);
        return $data;
    }
}

