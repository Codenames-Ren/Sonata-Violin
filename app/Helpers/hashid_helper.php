<?php

use Hashids\Hashids;

if (!function_exists('encode_id')) {
    function encode_id($id, $salt = null)
    {
        $salt = $salt ?? env('encryption.key', 'sonata-default-salt-2024');
        $hashids = new Hashids($salt, 12); // min length 12 karakter
        return $hashids->encode($id);
    }
}

if (!function_exists('decode_id')) {
    function decode_id($hash, $salt = null)
    {
        $salt = $salt ?? env('encryption.key', 'sonata-default-salt-2024');
        $hashids = new Hashids($salt, 12);
        $decoded = $hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }
}

if (!function_exists('encode_ids')) {
    function encode_ids(array $ids, $salt = null)
    {
        $salt = $salt ?? env('encryption.key', 'sonata-default-salt-2024');
        $hashids = new Hashids($salt, 12);
        return $hashids->encode(...$ids);
    }
}

if (!function_exists('decode_ids')) {
    function decode_ids($hash, $salt = null)
    {
        $salt = $salt ?? env('encryption.key', 'sonata-default-salt-2024');
        $hashids = new Hashids($salt, 12);
        return $hashids->decode($hash);
    }
}