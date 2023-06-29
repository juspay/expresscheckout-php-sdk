<?php
namespace Juspay\Model;
abstract class ISign
{
    abstract public function sign(string $key, string $data) : string;
    abstract public function verifySign(string $key, string $data) : string;
}
?>