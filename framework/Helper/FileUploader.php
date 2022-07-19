<?php
    namespace framework\Helper;
    class FileUploader{
        public static $message,$returnedPath;
        public  const  IMAGE="image";
        public  const  VIDEO="video";
        public  const  AUDIO="audio";
        public  const  DOCUMENT="document";
        private static $file,$type;
        private static $properties = array(
            "image" => array(
                "valid_extension"   => array("jpg","jpeg","png","gif","webp"),
                "size"              => 4194304
            ),
            "video" => array(
                "valid_extension"   => array("mp4","3gp","wmv"),
                "size"              => 10485760,
            ),
            "audio" => array(
                "valid_extension"   => array("mp3"),
                "size"              => 3145728
            ),
            "document"  => array(
                "valid_extension"   => array("pdf","rtf","doc","docx","xls","xlsx","ppt","pptx","pps"),
                "size"              => 3145728
            )
        );
        public static function upload($file,$type = 'image'){
            self::$file = $file;
            self::$type = $type;
            if(self::notEmpty()){
                if(self::isValid()){
                    if($ext = self::checkExtension()){
                        if(self::checkSize()){
                            return self::finallyUpload($ext);
                        }
                    }else{
                        self::$message = self::getExtensionMessage();
                        return false;
                    }
                }
            }else{
                self::$message = "Select a File";
                return false;
            }
        }
        private static function isValid(){
            if(!$_FILES[self::$file]['error']){
                return true;
            }else{
                self::$message = "Error in File";
                return false;
            }
        }
        private static function checkExtension(){
            $ext = strtolower(pathinfo($_FILES[self::$file]['name'],PATHINFO_EXTENSION));
            switch(self::$type){
                case "image":
                   return (in_array($ext,self::$properties["image"]["valid_extension"])) ? $ext : false;
                case "video":
                    return (in_array($ext,self::$properties["video"]["valid_extension"])) ? $ext : false;
                case "audio":
                    return (in_array($ext,self::$properties["audio"]["valid_extension"])) ? $ext : false;
                case "document":
                    return (in_array($ext,self::$properties["document"]["valid_extension"])) ? $ext : false;
                case "image|document":
                    return (in_array($ext,self::$properties["image"]["valid_extension"]) || in_array($ext,static::$properties["document"]["valid_extension"])) ? $ext : false;
                default:
                    return false;
            }
        }
        private static function getExtensionMessage(){
            $msg = "Invalid File! Only [ ";
            switch (self::$type){
                case "image":
                    $msg.=implode(", ",self::$properties["image"]["valid_extension"]);
                break;
                case "video":
                    $msg.=implode(", ",self::$properties["video"]["valid_extension"]);
                break;
                case "audio":
                    $msg.=implode(", ",self::$properties["audio"]["valid_extension"]);
                break;
                case "document":
                    $msg.=implode(", ",self::$properties["document"]["valid_extension"]);
                break;
                case "image|document":
                    $msg.=implode(", ",self::$properties["image"]["valid_extension"])." ";
                    $msg.=implode(", ",self::$properties["document"]["valid_extension"]);
                break;
                default:
                    $msg = false;
            }
            $msg.=" ] extensions are allowed";
            return $msg;
        }
        private static function checkSize(){
            $size = number_format($_FILES[self::$file]['size']/1048576,0);
            switch(self::$type){
                case "image":
                    $isize = number_format(self::$properties["image"]["size"]/1048576,0);
                    self::$message = "Image must be less or equal to ".$isize." MB ";
                    return ($size<=$isize);
                case "video":
                    $vsize = number_format(self::$properties["video"]["size"]/1048576,0);
                    self::$message = "Video must be less or equal to ".$vsize." MB ";
                    return ($size<=$vsize);
                case "audio":
                    $asize = number_format(self::$properties["audio"]["size"]/1048576,0);
                    self::$message = "Audio must be less or equal to ".$asize." MB ";
                    return ($size<=$asize);
                case "document":
                    $dsize = number_format(self::$properties["document"]["size"]/1048576,2);
                    self::$message = "Document must be less or equal to ".$dsize." MB ";
                    return ($size<=$dsize);
                case "image|document":
                    $isize = number_format(self::$properties["image"]["size"]/1048576,2);
                    $dsize = number_format(self::$properties["document"]["size"]/1048576,2);
                    self::$message = "File exceeds the upload size";
                    return (($size<=$isize) || ($size<=$dsize));
                default:
                    return false;
            }
        }
        private static function notEmpty(){
            return (!empty($_FILES[self::$file]['name']));
        }
        private static function renameFile($ext){
            return "upload_".rand(1000000,9999999)."_".time().".".$ext;
        }
        private static function finallyUpload($ext){
            $renImage = self::renameFile($ext);
            $npath = date('Y') . DS . date('m') . DS . date('d');
            $upath = UPLOAD_DIR.$npath;
            if(!file_exists($upath)){
                mkdir($upath,0777,true);
            }
            $fileToUpload = $upath. DS .$renImage;
            if(move_uploaded_file($_FILES[self::$file]['tmp_name'],$fileToUpload)){
                self::$returnedPath = str_replace(DS,"/",$npath)."/".$renImage;
                return true;
            }else{
                self::$message = "Error While Uploading the File!";
                return false;
            }
        }
    }
?>