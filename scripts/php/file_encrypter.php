<?php

//require_once "script_access_checker.php"; //Need to incldue this but it's throwing a repeatedly included error or whatever

//Move this outside web root.
//Move this outside web root.
//Move this outside web root.
//Move this outside web root.
//Move this outside web root.
//Move this outside web root.
//Move this outside web root.
//Move this outside web root.



class FileEncrypter{
    const METHOD = 'aes-256-ctr';
    const KEY = "BzItPol13213!@j&ukL90*&#xxzCVVJkl1!2!@94%433322";
    public static function encrypt($message, $encode = true)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        try{
         $ciphertext = @openssl_encrypt(
            $message,
            self::METHOD,
            self::KEY,
            OPENSSL_RAW_DATA,
            $nonce
         );
         $sendEncrypt=$nonce.$ciphertext;
         if(!$ciphertext)throw new Exception('Unable To Encrypt Data');
        }catch(Exception $e){
            $encode=false;
            $sendEncrypt=$e->getMessage();
        }
        if ($encode) {
            $sendEncrypt=base64_encode($sendEncrypt);
        }
        return $sendEncrypt;
    }
    public static function decrypt($message, $encoded = true){
        if ($encoded) {
         try{
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Unable To Decrypt Data');
            }else{
                $nonceSize = openssl_cipher_iv_length(self::METHOD);
                $nonce = mb_substr($message, 0, $nonceSize, '8bit');
                $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

                $plaintext = @openssl_decrypt(
                    $ciphertext,
                    self::METHOD,
                    self::KEY,
                    OPENSSL_RAW_DATA,
                    $nonce
                );
            }
         }catch(Exception $e){
            $plaintext=$e->getMessage();
         }
        }

        return $plaintext;
    }
}