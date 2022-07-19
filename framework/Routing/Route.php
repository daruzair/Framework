<?php
namespace framework\Routing;
use framework\Controller\BaseController;
use framework\Exception\ClassNotFound;
use framework\Exception\MethodNotFound;
use framework\Request\Request;

final class Route
{

    private $GETMETHOD="GET";
    private $POSTMETHOD="POST";
    private $DELETEMETHOD="DELETE";
    private $PUTMETHOD="PUT";
    private $Request=null;

    public static function middleware($want,$call){
        $requesturl="/";
        $isMatched=false;
        if(isset($_REQUEST["requesturl"])){
            $requesturl.=$_REQUEST["requesturl"];
        }
        $requesturl=rtrim($requesturl,'/');
        $want=rtrim($want,'/');
        $url=explode('/',$requesturl);
        $want=explode('/',$want);
        if(count($want)<=count($url)) {
            for ($i = 0; $i < count($want); $i++) {

                if (strlen($want[$i]) != 0) {
                    if ($want[$i] == $url[$i]) {
                        $isMatched = true;
                    } else {
                        $isMatched = false;
                    }
                } else {
                    $isMatched = true;
                }
            }
        }
        $Routing=new self();
            if($isMatched){
                if(!is_callable($call)){
                    $controller = $Routing->createobject($call);
                    $Routing->callMethod($controller,$call);
                }else{
                    $path =$call();
                    if(!empty($path))
                        include $path;
                }
            }
    }
    public static function get($url,$call){
        $Route=new self();
        if($Route->ComMethod($Route->GETMETHOD)){
            $Route->getstated($url,$call);
        }

    }
    public static function post($url,$call){
        $Route=new self();
        if($Route->ComMethod($Route->POSTMETHOD)){
            $Route->getstated($url,$call);
        }
    }
    public static function delete($url,$call){
        $Route=new self();
        if($Route->ComMethod($Route->DELETEMETHOD)){
            $Route->getstated($url,$call);
        }
    }
    public static function put($url,$call){
        $Route=new self();
        if($Route->ComMethod($Route->PUTMETHOD)){
            $Route->getstated($url,$call);
        }
    }
    private function ComMethod($method){
        if($_SERVER["REQUEST_METHOD"]===$method){
            return true;
        }
        return false;

    }
    private function getstated(string $url,$call){
        $this->Request=new Request();
    if($this->readyurl($url)) {
        if(!is_callable($call)){
            $controller = $this->createobject($call);
            $this->callMethod($controller,$call);
        }else{
            $path =$call();
            if(!empty($path))
                include VIEW_DIR.$path.".php";
        }
        exit();
    }
    }

    private function readyurl($want){
        $requesturl="/";
        if(isset($_REQUEST["requesturl"])){
            $requesturl.=$_REQUEST["requesturl"];
        }
        $requesturl=rtrim($requesturl,'/');

        $want=rtrim($want,'/');
        $url=explode('/',$requesturl);
        $want=explode('/',$want);
        return $this->checkurl($want,$url);

    }
    private function checkurl(array $want,array $url){
        $isMatched=false;
        if(count($want)===count($url)) {
            for ($i=0;$i<count($url); $i++){
                if(strlen($want[$i])!=0){
                    if($want[$i][0]=="?"){
                        $var=ltrim($want[$i],'?');
                        $this->Request->{$var}=$url[$i];
                        $isMatched=true;
                    }elseif($want[$i]==$url[$i]){
                        $isMatched=true;
                    }else{
                        $isMatched=false;
                        return $isMatched;
                    }
                }else{
                    if($want[$i]==$url[$i]){
                        $isMatched=true;
                    }else{
                        $isMatched=false;
                    }
                }
            }
        }
        return $isMatched;
    }
    private function createobject($instance){
        $instance=explode('.',$instance);
        $instance="\app\Controller\\".$instance[0];

        if(!class_exists($instance)){
            throw new ClassNotFound("Class Not Found You Want to Route ",'404');
        }
        return new $instance();
    }
    private function callMethod(BaseController $obj,string $methodname){
        $methodname=explode('.',$methodname);
        if(!isset($methodname[1])){
            throw new MethodNotFound("Method Not Specify in your Route ",'401');
        }
        $methodname=$methodname[1];
        if(!method_exists($obj,$methodname)){
            throw new MethodNotFound("Method Not Found You Want to Route ",'404');
        }
        $path=$obj->$methodname($this->Request);
        if(!empty($path))
        include VIEW_DIR.$path.".php";

    }

}
?>