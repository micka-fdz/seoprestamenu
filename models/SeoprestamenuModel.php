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
class SeoprestamenuModel extends ObjectModel
{
    public $id;

    public $type;

    public $target;
    
    public $cssclass;
    
    public $custom_parameter;

    public $url_engine;

    public $display_sections;

    public $id_parent;

    public $id_silo;

    public $position;

    public $label;

    public $url;

    public $id_lang;

    public $id_shop;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seoprestamenu_item',
        'primary' => 'id_item',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'type'          => array('type' => self::TYPE_STRING,  'size' => 255, 'required' => true),
            'target'        => array('type' => self::TYPE_STRING),
            'cssclass'        => array('type' => self::TYPE_STRING),
            'custom_parameter' => array('type' => self::TYPE_STRING),
            'url_engine'    => array('type' => self::TYPE_BOOL),

            'id_parent'     => array('type' => self::TYPE_INT),
            'id_silo'       => array('type' => self::TYPE_INT),
            'position'        => array('type' => self::TYPE_INT),

            /* Shop Fields */

            /* LANG FIELDS */
            'label' =>  array('type' => self::TYPE_STRING, 'lang' => true),
            'url'   =>  array('type' => self::TYPE_STRING, 'lang' => true),
            // 'id_lang'   =>  array('type' => self::TYPE_INT, 'lang' => true),
            // 'id_shop'   =>  array('type' => self::TYPE_INT, 'lang' => true),
            'display_sections'    => array('type' => self::TYPE_BOOL , 'lang' => true),

        ),
    );

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null)
    {
        parent::__construct($id_product, $id_lang, $id_shop);
    }

    public static function getItems($idLang = null, $idShop = null, $only_parents = false)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('seoprestamenu_item', 'i');
        if ($idLang) {
            if ($idShop) {
                $sql->innerJoin('seoprestamenu_item_lang', 'l', 'i.id_item = l.id_item AND l.id_lang = '.(int) $idLang.' AND l.id_shop = '.(int) $idShop);
            } else {
                $sql->innerJoin('seoprestamenu_item_lang', 'l', 'i.id_item = l.id_item AND l.id_lang = '.(int) $idLang);
            }
        }
        if ($only_parents) {
            $sql->where('i.id_parent = 0');
        }

        $sql->orderBy('position');

        return Db::getInstance()->executeS($sql);
    }

    public function add($auto_date = false, $null_values = false)
    {
        $this->position = (int)$this->getLastPosition();
        return parent::add($auto_date, $null_values);
    }

    public function getLastPosition()
    {
        $sql = new DbQuery();
        $sql->select("MAX(position) as pos");
        $sql->from("seoprestamenu_item");

        return Db::getInstance()->getRow($sql)['pos']+1;
    }

    public function getPositions($menu, $array = array(), $parent = 0, $position = 1)
    {
        foreach ($menu as $item):
            $positions = ['id' => $item['id'], 'name' => $item['name'], 'parent' => $parent, 'position' => $position ];
        $array[] = $positions;
        if (isset($item['children'])) {
            $array = $this->getPositions($item['children'], $array, $item['id'], 1);
        }

        $position++;
        endforeach;
        return $array;
    }

    public function updatePositions($menu_formated)
    {
        $values = "";
        $sql = "INSERT INTO "._DB_PREFIX_."seoprestamenu_item (id_item,id_parent,position) VALUES ";
        // (1,1,1),(2,2,3),(3,9,3),(4,10,12)
        foreach ($menu_formated as $item):
            $id 		= $item['id'];
        $id_parent 	= $item['parent'];
        $position 	= $item['position'];
        $values .= "($id,$id_parent,$position), ";
        endforeach;
        $values =  substr($values, 0, -2);
        $sql .= $values;
        $sql .= " ON DUPLICATE KEY UPDATE id_parent=VALUES(id_parent),position=VALUES(position);";
        return Db::getInstance()->execute($sql);
    }

    public function getChilden($id_parent, $id_lang)
    {
        $sql = new DbQuery();
        $sql->select("*");
        $sql->from("seoprestamenu_item", "c");
        $sql->innerJoin('seoprestamenu_item_lang', 'l', 'c.id_item = l.id_item AND l.id_lang = '.(int)$id_lang);
        $sql->where("c.id_parent = ".(int)$id_parent);
        $sql->orderBy('c.position');
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    
    public static function getIdsText($items, $key = null)
    {
        if (sizeof($items) > 0) {
            $wherein = '';
            foreach ($items as $item) {
                if ($key !== null) {
                    $wherein .= (int)$item[$key].',';
                } else {
                    $wherein .= (int)$item['id_item'].',';
                }
            }
            $wherein = substr($wherein, 0, -1);
            return $wherein;
        } else {
            return false;
        }
    }
    
    public static function getIds()
    {
        return Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."seoprestamenu_item` WHERE id_parent NOT IN (SELECT id_item FROM `"._DB_PREFIX_."seoprestamenu_item`) AND id_parent != 0");
    }

    public static function cleanMenuDb()
    {
        $items = self::getIds();
        if (sizeof($items) > 0) {
            $ids = self::getIdsText($items);
            if ($ids != false) {
                Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."seoprestamenu_item` SET id_parent = 0 WHERE id_item IN($ids)");
                // Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."seoprestamenu_item` WHERE id_item IN($ids)");
                //Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."seoprestamenu_item_lang` WHERE id_item  IN($ids)");
            }
        } else {
            return false;
        }
    }
}
