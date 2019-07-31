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
* @author Guillaume Batier seo-presta.com
* @copyright Guillaume Batier seo-presta.com
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once 'models/SeoprestamenuModel.php';
class Seoprestamenu extends Module implements WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'seoprestamenu';
        $this->tab = 'administration';
        $this->version = '1.0.2';
        $this->author = 'seopresta';
        $this->need_instance = 0;

        /*
        * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
        */
        $this->bootstrap = true;
        $context = Context::getContext();
        $this->current_lang = (int) $context->language->id;
        $this->id_shop = (int) $context->shop->id;
        $this->langs = Language::getLanguages(false, false);
        $this->token = $this->getToken();
        $this->ajax_url = _MODULE_DIR_ . $this->name . '/ajax.php';
        $this->menu_model = new SeoprestamenuModel();
        $context->smarty->assign('langs', $this->langs);
        $context->smarty->assign('helperMenu', $this);

        parent::__construct();

        $this->displayName = $this->l('Smart menu');
        $this->description = $this->l('Smart menu - a new menu 100% seo friendly');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:seoprestamenu/views/templates/hooks/displayNavFullWidth.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('SEOPRESTAMENU_LIVE_MODE', false);
        include dirname(__FILE__) . '/sql/install.php';
        $this->installDefaultCat();
        if (Module::isInstalled('ps_mainmenu')) {
            Module::disableByName('ps_mainmenu');
        }

        return parent::install() &&
            $this->generateToken() &&
            $this->registerHook('header') &&
            $this->registerHook('displayNavFullWidth') &&
            $this->registerHook('backOfficeHeader') &&
            $this->installHook('displaySubmenuBefore','','This hook is launched in seoprestamenu before displaying a submenu item') &&
            $this->installHook('displaySubmenuAfter','','This hook is launched in seoprestamenu after displaying a submenu item');
    }

    public function installHook($hook_name, $title ='', $description ='')
    {
        $return = true;
        // check if hook already exists
        if (Hook::getIdByName($hook_name)) {
            return true;
        }
        $new_hook = new Hook();
        $new_hook->name = pSQL($hook_name);
        $new_hook->title = ($title)?pSQL($title):pSQL($hook_name);
        $new_hook->description = pSQL($description);
        $new_hook->position = 1;

        if (!$new_hook->add()) {
            $return &= false;
            $this->_errors[] = $this->l('Could not install new hook') . ': ' . $hook_name;
        }

        return $return;
    }

    /**
     * Install default Categories.
     *
     * @return void
     */
    public function installDefaultCat()
    {
        $root_cat = (object) Category::getRootCategory();
        $id_root = (int) $root_cat->id;
        $category_ids = Db::getInstance()->executeS('SELECT id_category FROM ' . _DB_PREFIX_ . 'category WHERE id_parent = ' . $id_root . ' AND active = 1 LIMIT 0,5');
        $langs = Language::getLanguages();
        $shop_id = Context::getContext()->shop->id;

        foreach ($category_ids as $category_id) {
            $menuModel = new SeoprestamenuModel();
            $menuModel->type = 'category';
            $menuModel->url_engine = 0;
            $menuModel->id_parent = 0;
            $menuModel->target = '_self';
            $menuModel->id_shop = $shop_id;
            $category_id = (int) $category_id['id_category'];
            foreach ($langs as $lang) {
                $lang_id = (int) $lang['id_lang'];
                $category = new Category($category_id, $lang_id, $shop_id);
                $menuModel->url[$lang_id] = $this->context->link->getCategoryLink($category, null, $lang_id);
                $menuModel->label[$lang_id] = pSQL($category->name);
            }
            $menuModel->add();
        }
    }

    public function installDB()
    {
        // install Database on intall module
        include __DIR__ . '/sql/install.php';
    }

    public function uninstall()
    {
        include __DIR__ . '/sql/uninstall.php';
        Configuration::deleteByName('SEOPRESTAMENU_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Generate a token at install FOR AJAX
     */
    private function generateToken()
    {
        $random = $this->generateRandomString();

        return Configuration::updateValue('_SEO_PRESTA_MENU_TOKEN_', $random);
    }

    private function getToken()
    {
        return Configuration::get('_SEO_PRESTA_MENU_TOKEN_');
    }

    /**
     * @return string
     *                generate random token
     */
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, Tools::strlen($characters) - 1)];
        }

        return md5($randomString);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        if (((bool) Tools::isSubmit('submitSeoprestamenuModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('menu', $this->getMenu());
        $this->context->smarty->assign('categoriesTree', $this->getCategoryTree(null, $this->current_lang));
        $this->context->smarty->assign('cmsPages', $this->getCMSPages());
        $this->context->smarty->assign('current_shop', $this->context->shop->id);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output;
    }

    public function getItemsByParent($items)
    {
        $itemsByParent = array();
        foreach ($items as $item) {
            $itemsByParent[$item['id_parent']][] = $item;
        }

        return $itemsByParent;
    }

    public function getMenu($id_lang = null, $id_shop = null)
    {
        if ($id_lang == null) {
            $id_lang = $this->current_lang;
        }

        if ($id_shop == null) {
            $id_shop = $this->id_shop;
        }

        $items = $this->menu_model->getItems($id_lang, $id_shop, true);

        $this->context->smarty->assign('items', $items);
        if ($id_lang !== $this->current_lang) {
            $this->context->smarty->assign('override_lang', $id_lang);
        }
        $this->context->smarty->assign('start', true);

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/menu.tpl');
    }

    public function displayAjaxForm($item, $id_lang)
    {
        $this->context->smarty->assign('item', $item);
        $this->context->smarty->assign('id_lang', $id_lang);

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/ajax_form_details.tpl');
    }

    //######## CATEGORIES MODELS

    public function constructTreeNode($node, $level = 0, &$array)
    {
        $x = '';
        $padding_left = 5;
        $name = preg_replace('#[^a-zA-Z]#', '', $node['name']);
        for ($i = 0; $i <= $level; ++$i) {
            $calcul_padding = $padding_left * $level . 'px';
            $x .= '<span style="padding-left:' . $calcul_padding . '">&nbsp;</span>';
        }
        $x .= '- ';

        $array[] = array(
            'value' => $node['id'],
            'text' => $x . stripcslashes($name),
        );

        if (!empty($node['children'])) {
            ++$level;
            foreach ($node['children'] as $child) {
                self::constructTreeNode($child, $level, $array);
            }
        }

        return $array;
    }

    public function getCategoriesRecursive()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $categTree = Category::getRootCategory()->recurseLiteCategTree(0);
        $array = array();
        $array[] = array(
            'value' => $categTree['id'],
            'text' => htmlspecialchars($categTree['name']),
        );

        foreach ($categTree['children'] as $child) {
            $output = self::constructTreeNode($child, 0, $array);
        }

        return $output;
    }

    public static function getCategoryTree($id_product, $id_lang, $name = 'categoryBox')
    {
        $module = new Seoprestamenu();
        $root = Category::getRootCategory();
        $selected_cat = array($root->id);
        $tree = new HelperTreeCategories('categories-treeview', $module->l('Choose a category'));
        $tree->setUseCheckBox(true)
            ->setAttribute('is_category_filter', $root->id)
            ->setRootCategory($root->id)
            ->setSelectedCategories($selected_cat)
            ->setUseSearch(false);

        return $tree->render();
    }

    //###### PRODUCTS MODELS

    public function searchProduct($id_lang, $expr)
    {
        $id_lang = (int) $id_lang;
        $expr = pSQL($expr);
        $db = Db::getInstance();
        $sql = 'SELECT pl.id_product, pl.name,p.reference, pl.link_rewrite FROM ' . _DB_PREFIX_ . 'product as p, ' . _DB_PREFIX_ . 'product_lang as pl ';
        $sql .= 'WHERE p.id_product = pl.id_product ';
        $sql .= "AND pl.id_lang = $id_lang ";
        $sql .= "AND pl.name LIKE '%" . $expr . "%'";

        return $db->executeS($sql);
    }

    //###### CMS PAGES WIDGETS

    public function getCMSPages()
    {
        return CMS::getCMSPages($this->current_lang, null, true, $this->context->shop->id);
    }

    //#### Menu model

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') === $this->name) {
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            $this->context->controller->addCSS($this->_path . 'views/css/material-icons.css');
            $this->context->controller->addCSS($this->_path . 'views/css/checkbox.css');
            $this->context->controller->addCSS($this->_path . 'views/sweetmodal/jquery.sweet-modal.min.css');
            $this->context->controller->addCSS($this->_path . 'views/sweetalert/sweetalert.css');

            // fix for version 1.7.4.2
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addJS($this->_path . 'views/js/jquery.nestable.js');
            $this->context->controller->addJS($this->_path . 'views/js/jquery.nestable++.js');
            $this->context->controller->addJS($this->_path . 'views/js/bootstrap-typeahead.js');
            $this->context->controller->addJS($this->_path . 'views/sweetmodal/jquery.sweet-modal.min.js');
            $this->context->controller->addJS($this->_path . 'views/sweetalert/sweetalert.min.js');
            $this->context->controller->addJS($this->_path . 'views/js/functions.js');
            $this->context->controller->addJS($this->_path . 'views/js/callmenu.js');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/menu-front.js');
        $this->context->controller->addJS($this->_path . '/views/js/jquery.nanoscroller.min.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function searchParentCategoryRecursive($id_category, $parent = 2)
    {
        if ($id_category == $parent) {
            return $id_category;
        }
        $c = new Category($id_category, $this->current_lang, $this->id_shop);
        if ($c->id_parent == $parent) {
            return $c->id;
        } else {
            return $this->searchParentCategoryRecursive($c->id_parent, $parent);
        }
    }

    /**
     * GetWidgetVariable WidgetInterface
     *
     * @param string $hookName
     * @param array $configuration
     *
     * @return array
     */
    public function getWidgetVariables($hookName, array $configuration = [])
    {
        $id_lang = $this->current_lang;
        $id_shop = $this->id_shop;
        $items = $this->menu_model->getItems($id_lang, $id_shop, true);
        $root = Category::getRootCategory();
        $id_root = $root->id;

        $items = $this->menu_model->getItems($id_lang, $id_shop, true);

        $detect = new Mobile_Detect();
        $widgetVariables = array(
            'items' => $items,
            'id_lang' => $id_lang,
            'root_cat' => $id_root,
            'is_mobile' => $detect->isMobile(),
            'is_tablet' => $detect->isTablet(),
            'helperMenu' => $this,
            'langs' => $this->langs,
            'root_link' => $this->context->link->getCategoryLink($root),
        );

        return $widgetVariables;
    }

    /**
     * RenderWidget
     *
     * @param string $hookName
     * @param array $configuration
     *
     * @return void
     */
    public function renderWidget($hookName, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch($this->templateFile);
    }
}
