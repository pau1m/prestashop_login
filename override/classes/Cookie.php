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

class Cookie extends CookieCore
{

	protected function getDomain($shared_urls = null)
	{
    $cookie_domain = Configuration::get('CUSTOMERLOGIN_DRUPALDOMAIN');

    if (!empty($cookie_domain)){
        return $cookie_domain;
    } else {

    Tools::displayError('Cookie retrieve cookie domain, is it set in module config?');

    $r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';

    if (!preg_match ($r, Tools::getHttpHost(false, false), $out) || !isset($out[4]))
     return false;

    if (preg_match('/^(((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]{1}[0-9]|[1-9]).)'.
     '{1}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]).)'.
     '{2}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]){1}))$/', $out[4]))
     return false;
    if (!strstr(Tools::getHttpHost(false, false), '.'))
     return false;

    $domain = false;
    if ($shared_urls !== null)
    {
     foreach ($shared_urls as $shared_url)
     {
       if ($shared_url != $out[4])
         continue;
       if (preg_match('/^(?:.*\.)?([^.]*(?:.{2,4})?\..{2,3})$/Ui', $shared_url, $res))
       {
         $domain = '.'.$res[1];
         break;
       }
     }
    }
    if (!$domain)
     $domain = $out[4];
     return  $domain;
     
    }
	}
}
