<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author freelance-addons.fr <zeltron2k3@gmail.com>
* @copyright 2018 freelance-addons
* @license   see file: LICENSE.txt
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'solidaritegiletsjaunes` (
    `id_solidaritegiletsjaunes` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY  (`id_solidaritegiletsjaunes`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
