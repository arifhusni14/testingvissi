<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Khalifa Esha <esha@inzpiretechnology.com> 2015
 */
class Enc
{
    protected $skey = '4r1fhu5n1';

    public function encode($value) {
        if (!$value) {
            return false;
        }

        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_size);
        $crypttext = openssl_encrypt($value, "aes-256-cbc", $this->skey, OPENSSL_RAW_DATA, $iv);

        return trim($this->safe_b64encode($iv . $crypttext));
    }

    public function decode($value) {
        if (!$value) {
            return false;
        }

        $crypttext = $this->safe_b64decode($value);
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        $iv = mb_substr($crypttext, 0, $iv_size, '8bit');
        $ciphertext = mb_substr($crypttext, $iv_size, null, '8bit');

        return openssl_decrypt($ciphertext, "aes-256-cbc", $this->skey, OPENSSL_RAW_DATA, $iv);
    }

    private function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    private function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data.= substr('====', $mod4);
        }
        return base64_decode($data);
    }

}