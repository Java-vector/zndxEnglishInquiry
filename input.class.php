<?php
class Input{
  //自定义函数，用于接收POST数据，并对传输的数据进行一定的检查
  function post( $name ){
    //判断post的值是否为空系统函数array_key_exists用于检查对应键在数组中是否存在对应的值
    if(array_key_exists($name,$_POST) == true){
      $value = $_POST[$name];
      return $value;
    }else
    return null;
  }
  function get( $name ){
    //判断post的值是否为空系统函数array_key_exists用于检查对应键在数组中是否存在对应的值
    if(array_key_exists($name,$_GET) == true){
      $value = $_GET[$name];
      return $value;
    }else
    return null;
  }
}
 ?>
