<?php
class WebserviceRequest extends WebserviceRequestCore
{

  public static function getResources() {
    $resources=parent::getResources();
    $resources['customer_login'] = array (
                                   'description' => 'Testing adding webservice class', 
                                   'specific_management' => TRUE, 
                                   'forbidden_method' => array('PUT', 'POST', 'DELETE', 'HEAD'),
                                   );
    ksort($resources);
    return $resources;
  }
}
