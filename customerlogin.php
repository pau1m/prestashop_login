<?php


//@todo IIRC the webservice in the core code is the ones thats been edited and should be copied out
//@todo rename and refactor (we don't need the custom rest stuff any more

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
    $this->description = $this->l('A clumsy SSO solution');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    // if (!Configuration::get('customerlogin')){      
    //   $this->warning = $this->l('No name provided');
    // }
  }

  public function getContent()
  {
      $output = null;
   
      if (Tools::isSubmit('submit' . $this->name))
      {

        Configuration::updateValue('CUSTOMERLOGIN_DRUPALURL', Tools::getValue('customerlogin_drupalurl'));
        Configuration::updateValue('CUSTOMERLOGIN_DRUPALDOMAIN', Tools::getValue('customerlogin_drupaldomain'));

        $output .= $this->displayConfirmation($this->l('Settings updated'));
      }
      return $output . $this->displayForm();
  }

  public function displayForm()
  {
      // Get default Language
      $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
       
      $fields_form = array(
        'form' => array(
          'legend' => array(
              'title' => $this->l('Settings'),
          ),
          'input' => array (
              array(
                  'type' => 'text',
                  'label' => $this->l('Cookie Domain'), 
                  'name' => 'customerlogin_drupaldomain',
                  'desc' => $this->l('This should be the TLD with a dot at the start eg .example.com'),
                  'size' => 20,
                  'required' => true
              ),
              array(
                  'type' => 'text',
                  'label' => $this->l('Drupal URL'), 
                  'name' => 'customerlogin_drupalurl',
                  'desc' => $this->l('URL of accompanying Drupal install. Include the http:// and a trailing slash to give the form http://example.com/'),
                  'size' => 20,
                  'required' => true
              ),
          ),
          'submit' => array(
              'title' => $this->l('Save'),
              //'class' => 'button'
          ),
        ),
      );
       
      $helper = new HelperForm();
       
      // Module, token and currentIndex
      $helper->module = $this;
      $helper->name_controller = $this->name;
      $helper->token = Tools::getAdminTokenLite('AdminModules');
      $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
       
      // Language
      $helper->default_form_language = $default_lang;
      $helper->allow_employee_form_lang = $default_lang;
      //    $this->fields_form = array();
      // Title and toolbar
      $helper->title = $this->displayName;
       $helper->show_toolbar = false;        // false -> remove toolbar
       $helper->toolbar_scroll = false;      // yes - > Toolbar is always visible on the top of the screen.
      $helper->submit_action = 'submit'.$this->name;

      $helper->toolbar_btn = array(
          'save' =>
          array(
              'desc' => $this->l('Save'),
              'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
              '&token='.Tools::getAdminTokenLite('AdminModules'),
          ),
          'back' => array(
              'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
              'desc' => $this->l('Back to list')
          )
      );

       $helper->fields_value = $this->getConfigFieldsValues();

      return $helper->generateForm(array($fields_form));


  }



  public function install()
  {
    return (parent::install()
      && Configuration::updateValue('CUSTOMERLOGIN_DRUPALURL', '')
      && Configuration::updateValue('CUSTOMERLOGIN_DRUPALDOMAIN', ''));
  }

  public function uninstall()
  {

    return ( parent::uninstall()
      && Configuration::deleteByName('CUSTOMERLOGIN_DRUPALURL') 
      && Configuration::deleteByName('CUSTOMERLOGIN_DRUPALDOMAIN'));
  }

    public function getConfigFieldsValues()
  {
    return array(
      'customerlogin_drupalurl' => Tools::getValue('customerlogin_drupalurl', Configuration::get('CUSTOMERLOGIN_DRUPALURL')),
      'customerlogin_drupaldomain' => Tools::getValue('customerlogin_drupaldomain', Configuration::get('CUSTOMERLOGIN_DRUPALDOMAIN')),
    );
  }
}

