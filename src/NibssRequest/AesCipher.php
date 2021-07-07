<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\NirsalAgc\Plugins\NibssRequest;

use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;

/**
 * class AesCipher.
 *
 * AesCipher Class
 *
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since v0.0.1 07/07/2021 14:31
 */
class AesCipher {

    private const OPENSSL_CIPHER_NAME = "aes-128-cbc";
    private const CIPHER_KEY_LEN = 16; //128 bits

    private static function fixKey($key) {
        
        if (strlen($key) < AesCipher::CIPHER_KEY_LEN) {
            //0 pad to len 16
            return str_pad("$key", AesCipher::CIPHER_KEY_LEN, "0"); 
        }
        
        if (strlen($key) > AesCipher::CIPHER_KEY_LEN) {
            //truncate to 16 bytes
            return substr($key, 0, AesCipher::CIPHER_KEY_LEN); 
        }

        return $key;
    }

    /**
    * Encrypt data using AES Cipher (CBC) with 128 bit key
    * 
    * @param type $key - key to use should be 16 bytes long (128 bits)
    * @param type $iv - initialization vector
    * @param type $data - data to encrypt
    * @return encrypted data in base64 encoding with iv attached at end after a :
    */
    static function encrypt($key, $iv, $data) {

        $encodedEncryptedData = openssl_encrypt($data, AesCipher::OPENSSL_CIPHER_NAME, AesCipher::fixKey($key), OPENSSL_RAW_DATA, $iv);

        return bin2hex($encodedEncryptedData);
    }

    /**
    * Decrypt data using AES Cipher (CBC) with 128 bit key
    * 
    * @param type $key - key to use should be 16 bytes long (128 bits)
    * @param type $data - data to be decrypted in base64 encoding with iv attached at the end after a :
    * @return decrypted data
    */
    static function decrypt($key, $iv, $data) {
        $encrypted = hex2bin($data);
        $decryptedData = openssl_decrypt($encrypted, AesCipher::OPENSSL_CIPHER_NAME, AesCipher::fixKey($key), OPENSSL_RAW_DATA, $iv);

        return $decryptedData;
    }
};

?>