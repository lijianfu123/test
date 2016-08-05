<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */
require_once(APP_ROOT_PATH."system/common.php");
require_once(CLASS_PATH."ErrorCase.class.php");
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
    	es_session::start();
        $this->error = new ErrorCase();

        //-------读取配置文件
        $incFileContents = file_get_contents(APP_ROOT_PATH."public/qqv2_inc.php");
        $this->inc = json_decode($incFileContents);
        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(!es_session::is_set("QC_userData")){
            self::$data = array();
        }else{
            self::$data = es_session::get("QC_userData");
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
        es_session::set("QC_userData",self::$data);
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        es_session::set("QC_userData",self::$data);
    }
}