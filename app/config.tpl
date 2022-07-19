<?php
    require_once 'Statuscodes.php';
    define('WEBSITE','');
    define('DS',DIRECTORY_SEPARATOR);
    define('HTTP_HOST','http://'.$_SERVER["HTTP_HOST"]);
    define('STYLE',HTTP_HOST.'app/Style/');
    define('IMAGES',HTTP_HOST.'app/Images/');
    define('ICONS',HTTP_HOST.'app/Icons/');
    define('INCLUDE',HTTP_HOST.'app/Include/');
    define('SCRIPT',HTTP_HOST.'app/Script/');
    
    define('UPLOADS',HTTP_HOST.'app/Uploads/');
    define('UP_IMAGES',HTTP_HOST.'app/Uploads/Images/');
    define('UP_VIDEOS',HTTP_HOST.'app/Uploads/Videos/');
    define('UPLOAD_DIR',__DIR__.DS.'Include'.DS);
    define('VENDOR_DIR',__DIR__.DS.'vendor'.DS);
    define('INCLUDE_DIR',__DIR__.DS.'Uploads'.DS.'Images'.DS);
    define('VIEW_DIR',__DIR__.DS.'View'.DS);
    define('VENDER_DIR',__DIR__.DS.'vender'.DS);
    define('LAYOUT_DIR',VIEW_DIR.'layout'.DS);
    define('COMPONENTS_DIR',VIEW_DIR.'components'.DS);
    define('ADMIN_MAIL_HOST','');
    define('ADMIN_MAIL','daruzair440@gmail.com');
    define('ADMIN_MAIL_PASS','');

    
    define('DB_NAME','DbName');
    define('DB_HOST','Host:');
    define('DB_PORT','Port');
    define('DB_USER','DbUser');
    define('DB_PASS','Password');
?>