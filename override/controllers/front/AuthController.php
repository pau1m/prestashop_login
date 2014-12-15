<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


//from the docs

/*
In order to override the ProductController class, your file needs to be called ProductController.php and must feature a ProductController class that then extends ProductControllerCore class.
The file can be placed in either of these locations:
/override/controllers/front/ProductController.php
/modules/my_module/override/controllers/front/ProductController.php

but it doesn't seem to work :/
*/   

// @see http://doc.prestashop.com/display/PS16/Overriding+default+behaviors
class AuthController extends AuthControllerCore
{
  public $ssl = true;
  public $php_self = 'authentication';

  /**
   * @var bool create_account
   */
  protected $create_account;

  /**
   * Initialize auth controller
   * @see FrontController::init()
   */
  public function init()
  {

   // if (!$this->context->customer->isLogged()) {

    //drupal assumes any path used in destination is a local drupal path
    //will need to setup a new path in drupal like presta/redirect/% that will act as a proxy
    //to forward back to whence the user came with drupal_goto

    //same to with logout -> need to pass the user to /user/logout then redirect back to whence they came from the sh
    if (!$this->context->customer->isLogged()) {

        $drupal_domain = Configuration::get('CUSTOMERLOGIN_DRUPALURL');
        //@todo add validation
        if (!empty($drupal_domain)){
          Tools::redirect($drupal_domain . 'user?destination=presta-redirect/my-account');
        } else {
          displayError('Cookie domain not set in configuration');
    }



      Tools::redirect('http://llrsso.dev/user?destination=presta-redirect/my-account');
    }

    parent::init();

    if (!Tools::getIsset('step') && $this->context->customer->isLogged() && !$this->ajax) {
      Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));
    }

    if (Tools::getValue('create_account')) {
      $this->create_account = true;
    }
  }


}