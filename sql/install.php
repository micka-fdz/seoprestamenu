<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seoprestamenu_item` (
  `id_item` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `cssclass` varchar(255) NOT NULL,
  `custom_parameter` varchar(1000) NOT NULL,
  `url_engine` tinyint(1) NOT NULL DEFAULT 0,
  `id_parent` int(11) NOT NULL DEFAULT 0,
  `id_silo` int(11) NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL DEFAULT 0
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seoprestamenu_item_lang` (
  `id_item` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  `label` text NOT NULL,
  `url` text NOT NULL,
  `display_sections` tinyint(1) NOT NULL DEFAULT 0
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seoprestamenu_item`
  ADD PRIMARY KEY (`id_item`);';

$sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seoprestamenu_item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;COMMIT;';

$return = false;
foreach ($sql as $query) {
    $res = Db::getInstance()->execute($query);
    if ($res == false) {
        return false;
    }
    $return &= $res;
}
return $return;
