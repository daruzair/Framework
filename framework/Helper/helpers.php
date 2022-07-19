<?php
require_once "Validater.php";

function autoloding(){
    spl_autoload_register(function($classname){
        $path='..'.DIRECTORY_SEPARATOR;
        $path.=$classname;
        $path.='.php';
        $path=str_ireplace('\\',DIRECTORY_SEPARATOR,$path);
        if(!is_file($path)){
            return false;
        }
        include_once $path;
    });
}
autoloding();
function encrypt($value){
    $salt=md5(WEBSITE);
    return password_hash($salt.$value.$salt,PASSWORD_DEFAULT );
}
function check($value,$hashalue){
    $salt=md5(WEBSITE);
    return password_verify($salt.$value.$salt,$hashalue );
}
function AuthToken(){
    $charvalue="";
    $rand=rand(10,50);
    for ($i=0; $i<$rand; $i++){
        $randvalue=rand(65,90);
        $charvalue.=chr($randvalue);
    }
    return md5($charvalue);
}
function AuthField($token){
    return "<input type='hidden' name='AuthToken' value='".$token."'>";
}
function ConformationCode(){
    return rand(111111,999999);
}
function ConformationCodeEmail($email,$ConformationCode){

}