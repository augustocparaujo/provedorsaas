<?php
/**
 * Bootstrapping File for phpseclib
 *
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

if (extension_loaded('mbstring')) {
    // 2 - MB_OVERLOAD_STRING
    // mbstring.func_overload is deprecated in php 7.2 and removed in php 8.0.
    if (version_compare(PHP_VERSION, '8.0.0') < 0 && ini_get('mbstring.func_overload') & 2) {
        throw new \UnexpectedValueException(
            'Overloading of string functions using mbstring.func_overload ' .
            'is not supported by phpseclib.'
        );
    }
}

include 'Net/SSH2.php';
    //ip da vps 89.163.224.149
    $ssh = new Net_SSH2('89.163.224.149');
    if (!$ssh->login('sombra', 'k8TFnwIKajyaDd')) {
        exit('Login Failed');
    }

    //echo $ssh->exec('pwd');
    //echo $ssh->exec('ls -la');
    echo'conectado e RB';
    echo $ssh->exe('/interface print');

?>
