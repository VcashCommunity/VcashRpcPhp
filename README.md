# VcashRpcPhp

PHP Library for **Vcash** rpc commands

Need running Vcash daemon, if you don't know what's it, do not use this library

This library uses **JsonRpcClient** from **jsonrpcx-php** library
https://github.com/jenolan/jsonrpcx-php/

Usage example:

`$info = VcashRpc::rpc_getinfo()`
