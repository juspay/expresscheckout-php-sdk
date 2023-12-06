<?php
namespace Juspay\JWT;

final class Base64Url
{
    /**
     * @param string $data
     * @param bool   $use_padding
     *
     * @return string
     */
    public static function encode($data, $use_padding = false)
    {
        $encoded = strtr(base64_encode($data), '+/', '-_');

        return true === $use_padding ? $encoded : rtrim($encoded, '=');
    }

    /**
     * @param string $data The data to decode
     *
     * @return string The data decoded
     */
    public static function decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
