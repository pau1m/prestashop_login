<?php

//@todo rename and refactor (we don't need the custom rest stuff any more)

if (!defined('_PS_VERSION_')){
  exit;
}

class CustomerLogin extends Module
{
  public function __construct()
  {
    $this->name = 'customerlogin';
    $this->tab = 'others';
    $this->version = '0.0.1';
    $this->author = 'pjm llr';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Customer Login');
    $this->description = $this->l('Authenticate a customer via webservice');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    if (!Configuration::get('MYMODULE_NAME')){      
      $this->warning = $this->l('No name provided');
    }
  }

public function install()
{
  if (!parent::install())
    return false;
  return true;
}

public function uninstall()
{
  if (!parent::uninstall() ||
    !Configuration::deleteByName('MYMODULE_NAME'))
    return false;
  return true;
}



}

