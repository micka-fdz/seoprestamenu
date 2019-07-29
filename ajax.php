<?php

/*
* @ajax file
* @name seoprestathemeadmin
* @license seopresta
* @version 1.0.0
* @author Guillaume Batier prestasafe.com
* @copyright Guillaume Batier prestasafe.com
*/

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once 'seoprestamenu.php';

$module     = new Seoprestamenu;
$goodToken  = Configuration::get('_SEO_PRESTA_MENU_TOKEN_');

if (Tools::isSubmit('token') && Tools::getValue('token') == $goodToken) {
    // OK for ajax
    $action = null;
    if (Tools::getIsset('action')) {
        $action = Tools::getValue('action');
    }
    ###### Send category (alone)
    if ($action == 'sendItemMenu') {
        $html = "";
        $link = new Link;
        $cats = Tools::getValue('categoryBox');
        $mapping = array();
        foreach ($cats as $cat) {
            $c  = new Category($cat, $module->current_lang);
            
            $module->menu_model->type       = "category";
            $module->menu_model->target     = "_self";
            $module->menu_model->url_engine = false;
            
            $module->menu_model->id_parent  = (isset($mapping[$c->id_parent])) ? $mapping[$c->id_parent] : 0;
            $langs = Language::getLanguages();
            if (sizeof($langs) > 1) {
                foreach ($langs as $l) {
                    $cl  = new Category($cat, $l['id_lang']);
                    $module->menu_model->label[$l['id_lang']]   = $cl->name;
                    $module->menu_model->url[$l['id_lang']]     = $link->getCategoryLink($cl,null,(int)$l['id_lang']);
                    if(Tools::getIsset('id_shop'))
                    {
                        $id_shop = (int)Tools::getValue('id_shop');
                        $module->menu_model->id_shop = $id_shop;
                    }
                }
                
            } else {
                $module->menu_model->label[$module->current_lang]   = $c->name;
                $module->menu_model->url[$module->current_lang]     = $c->getLink();
                if(Tools::getIsset('id_shop'))
                {
                    $id_shop = (int)Tools::getValue('id_shop');
                    $module->menu_model->id_shop     = $id_shop;
                }
            }
            $module->menu_model->add();
            $mapping[$c->id_category] = $module->menu_model->id;
            
            $html .= '<li class="dd-item" data-id="'.$cat.'" data-name="'.$c->name.'" data-slug="item-slug-'.$cat.'" data-new="0" data-deleted="0">';
            $html .= '<div class="dd-handle">'.$c->name.'</div>';
            $html .= '<span class="button-delete btn btn-default btn-xs pull-right"';
            $html .= '	data-owner-id="'.$cat.'">';
            $html .= '	<i class="icon icon-times-circle-o" aria-hidden="true"></i>';
            $html .= '</span>';
            $html .= '<span class="button-edit btn btn-default btn-xs pull-right"';
            $html .= '			data-owner-id="'.$cat.'">';
            $html .= '	<i class="icon icon-pencil" aria-hidden="true"></i>';
            $html .= '</span>';
            $html .= '</li>';
        }
        echo $html;
        die();
    }
    
    ###### Send CMS page
    
    if ($action == 'sendCmsPageMenu') {
        $html = "";
        
        foreach (Tools::getValue('cms') as $cms) {
            $c  = new CMS($cms, $module->current_lang);
            $module->menu_model->type       = "cms";
            $module->menu_model->target     = "_self";
            $module->menu_model->url_engine = false;
            $module->menu_model->id_parent  = 0;
            
            $link = new Link;
            $langs = Language::getLanguages();
            if (sizeof($langs) > 1) {
                foreach ($langs as $l) {
                    $cl  = new CMS($cms, (int)$l['id_lang']);
                    $module->menu_model->label[$l['id_lang']]   = $cl->meta_title;
                    $module->menu_model->url[$l['id_lang']]     = $link->getCMSLink($cl,null, null, (int)$l['id_lang']);
                    if(Tools::getIsset('id_shop'))
                    {
                        $id_shop = (int)Tools::getValue('id_shop');
                        $module->menu_model->id_shop = $id_shop;
                    }
                }
            } else {
                $module->menu_model->label[$module->current_lang]   = $c->meta_title;
                $module->menu_model->url[$module->current_lang]     = $link->getCMSLink($c);
                if(Tools::getIsset('id_shop'))
                {
                    $id_shop = (int)Tools::getValue('id_shop');
                    $module->menu_model->id_shop = $id_shop;
                }
            }
            
            $module->menu_model->add();
            
            $html .= '<li class="dd-item" data-id="'.$cms.'" data-name="'.$c->meta_title.'" data-slug="item-slug-'.$cms.'" data-new="0" data-deleted="0">';
            $html .= '<div class="dd-handle">'.$c->meta_title.'</div>';
            $html .= '<span class="button-delete btn btn-default btn-xs pull-right"';
            $html .= '	data-owner-id="'.$cms.'">';
            $html .= '	<i class="icon icon-times-circle-o" aria-hidden="true"></i>';
            $html .= '</span>';
            $html .= '<span class="button-edit btn btn-default btn-xs pull-right"';
            $html .= '			data-owner-id="'.$cms.'">';
            $html .= '	<i class="icon icon-pencil" aria-hidden="true"></i>';
            $html .= '</span>';
            $html .= '</li>';
        }
        echo $html;
        die();
    }
    
    ##### Send custom Link
    if ($action == 'sendCustomLink') {
        $link = htmlspecialchars(Tools::getValue('link'));
        $label = htmlspecialchars(Tools::getValue('label'));
        $target = htmlspecialchars(Tools::getValue('target'));
        $cssclass = htmlspecialchars(Tools::getValue('cssclass'));
        $custom_parameter = htmlspecialchars(Tools::getValue('custom_parameter'));
        
        $module->menu_model->type       = "custom_link";
        $module->menu_model->target     = $target;
        $module->menu_model->cssclass   = $cssclass;
        $module->menu_model->custom_parameter   = $custom_parameter;
        $module->menu_model->url_engine = false;
        $module->menu_model->id_parent  = 0;
        
        
        $langs = Language::getLanguages();
        foreach ($langs as $l) {
            $module->menu_model->label[$l['id_lang']]   = $label;
            $module->menu_model->url[$l['id_lang']]     = $link;
            if(Tools::getIsset('id_shop'))
            {
                $id_shop = (int)Tools::getValue('id_shop');
                $module->menu_model->id_shop = $id_shop;
            }
        }
        
        
        $module->menu_model->add();
        
        $random = $module->menu_model->id;
        $html = "";
        
        
        $html .= '<li class="dd-item" data-id="'.$random.'" data-name="'.$label.'" data-slug="item-slug-'.$random.'" data-new="0" data-deleted="0">';
        $html .= '<div class="dd-handle">'.$label.'</div>';
        $html .= '<span class="button-delete btn btn-default btn-xs pull-right"';
        $html .= '	data-owner-id="'.$random.'">';
        $html .= '	<i class="icon icon-times-circle-o" aria-hidden="true"></i>';
        $html .= '</span>';
        $html .= '<span class="button-edit btn btn-default btn-xs pull-right"';
        $html .= '			data-owner-id="'.$random.'">';
        $html .= '	<i class="icon icon-pencil" aria-hidden="true"></i>';
        $html .= '</span>';
        $html .= '</li>';
        
        echo $html;
        die();
    }
    
    ##### Search product for autocomplete
    if ($action == 'searchProduct') {
        $link = new Link;
        $id_lang = (int)$module->current_lang;
        if(Tools::getIsset('id_lang'))
        {
            $id_lang = (int)Tools::getValue('id_lang');
        }
        
        $output = array();
        $results = $module->searchProduct($id_lang, pSQL(Tools::getValue('query')));
        if (sizeof($results) > 0) {
            foreach ($results as $p):
                $output[] = [
                    'name'      => ($p['reference'] != '') ? $p['name'].' ('.$p['reference'].')' : $p['name'],
                    'real_name' => $p['name'],
                    'id'        => $p['id_product'],
                    'link'      => $link->getProductLink($p['id_product'], $p['link_rewrite'], null, null, $module->current_lang)
                    
                ];
            endforeach;
        }
        $output = json_encode($output);
        echo $output;
        
        die();
    }
    
    
    
    ### getProduct by id.
    
    if ($action == 'getProductById') {
        $link = new Link;
        $id_product = (int)Tools::getValue('id_product');
        $product    = new Product($id_product, true, $module->current_lang);
        $product->custom_link = $link->getProductLink($product, $product->link_rewrite, $product->category, null, $module->current_lang);
        echo json_encode($product);
        
        die();
    }
    
    ##### Send product to menu
    if ($action == 'addProductMenu') {
        // $link = htmlspecialchars(Tools::getValue('link'));
        $label = htmlspecialchars(Tools::getValue('label'));
        $target = htmlspecialchars(Tools::getValue('target'));
        $cssclass = htmlspecialchars(Tools::getValue('cssclass'));
        $custom_parameter = htmlspecialchars(Tools::getValue('custom_parameter'));
        $id_product = (int)Tools::getValue('id_product');
        
        $module->menu_model->type       = "product";
        $module->menu_model->target     = $target;
        $module->menu_model->cssclass   = $cssclass;
        $module->menu_model->custom_parameter   = $custom_parameter;
        $module->menu_model->url_engine = false;
        $module->menu_model->id_parent  = 0;
        
        $link = new Link;
        $langs = Language::getLanguages();
        foreach ($langs as $l) {
            $module->menu_model->label[$l['id_lang']]   = $label;
            $module->menu_model->url[$l['id_lang']]     = $link->getProductLink($id_product,null,null,null,(int)$l['id_lang']);
        }
        
        
        $module->menu_model->add();
        
        $random = $module->menu_model->id;
        $html = "";
        
        
        $html .= '<li class="dd-item" data-id="'.$random.'" data-name="'.$label.'" data-slug="item-slug-'.$random.'" data-new="0" data-deleted="0">';
        $html .= '<div class="dd-handle">'.$label.'</div>';
        $html .= '<span class="button-delete btn btn-default btn-xs pull-right"';
        $html .= '  data-owner-id="'.$random.'">';
        $html .= '  <i class="icon icon-times-circle-o" aria-hidden="true"></i>';
        $html .= '</span>';
        $html .= '<span class="button-edit btn btn-default btn-xs pull-right"';
        $html .= '      data-owner-id="'.$random.'">';
        $html .= '  <i class="icon icon-pencil" aria-hidden="true"></i>';
        $html .= '</span>';
        $html .= '</li>';
        
        echo $html;
        die();
    }
    
    
    ### updateMenu
    
    if ($action == 'updateMenu') {
        $id_lang = (Tools::getIsset('id_lang')) ? ((int)Tools::getValue('id_lang')) : $module->current_lang;
        $id_shop = (Tools::getIsset('id_shop')) ? (int)Tools::getValue('id_shop') : 1;
        $menu_formated = $module->menu_model->getPositions($_POST['menu'], $array = array(), $parent = 0, $position = 1);
        echo $module->menu_model->updatePositions($menu_formated);
        
        die();
    }
    ### reloadMenu
    
    if ($action == 'reloadMenu') {
        $id_lang = (Tools::getIsset('id_lang')) ? ((int)Tools::getValue('id_lang')) : $module->current_lang;
        $id_shop = (Tools::getIsset('id_shop')) ? (int)Tools::getValue('id_shop') : 1;
        echo $module->getMenu($id_lang, $id_shop);
        
        die();
    }
    if ($action == 'removeItem') {
        $id_item    = (int)Tools::getValue('id');
        $id_parent  = (int)Tools::getValue('id_parent');
        $id_lang    = $module->current_lang;
        
        $sql        = "DELETE FROM "._DB_PREFIX_."seoprestamenu_item WHERE id_item = $id_item";
        $sql_lang   = "DELETE FROM "._DB_PREFIX_."seoprestamenu_item_lang WHERE id_item = $id_item AND id_lang = $id_lang";
        
        $db = Db::getInstance();
        $db->execute($sql);
        $db->execute($sql_lang);
        // if($id_parent > 0)
        // {
            //   $sql =
            // }
            die();
        }
        
        if ($action == "removeItems") {
            $ids   = (array)Tools::getValue('ids');
            if (sizeof($ids) > 0) {
                $wherein = "";
                foreach ($ids as $w) {
                    $id = (int)$w;
                    $wherein .= "$id,";
                }
                $id_lang    = $module->current_lang;
                $wherein = substr($wherein, 0, -1);
                $db = Db::getInstance();
                // $db->execute($sql_lang);
                $sql        = "DELETE FROM "._DB_PREFIX_."seoprestamenu_item WHERE id_item IN ($wherein)";
                $db->execute($sql);
                $sql_lang   = "DELETE FROM "._DB_PREFIX_."seoprestamenu_item_lang WHERE id_item IN ($wherein) AND id_lang = $id_lang";
                $db->execute($sql_lang);
                
                
                SeoprestamenuModel::cleanMenuDb();
            }
        }
        if ($action == 'getItemDetails') {
            // title: {
                //   tab1: {
                    //     label: 'Tab 1',
                    //     icon: '<svg style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="#000000" d="M12,19.2C9.5,19.2 7.29,17.92 6,16C6.03,14 10,12.9 12,12.9C14,12.9 17.97,14 18,16C16.71,17.92 14.5,19.2 12,19.2M12,5A3,3 0 0,1 15,8A3,3 0 0,1 12,11A3,3 0 0,1 9,8A3,3 0 0,1 12,5M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" /></svg>'
                    //   },
                    //
                    //   tab2: {
                        //     label: 'Tab 2',
                        //     icon: '<svg style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="#000000" d="M12,17L7,12H10V8H14V12H17L12,17M21,16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V7.5C3,7.12 3.21,6.79 3.53,6.62L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.79,6.79 21,7.12 21,7.5V16.5M12,4.15L5,8.09V15.91L12,19.85L19,15.91V8.09L12,4.15Z" /></svg>'
                        //   }
                        // },
                        //
                        // content: {
                            //   tab1: '<span class="toto">COUCOU</span>',
                            //   tab2: 'Tab 2'
                            // }
                            $id_item  = (int)Tools::getValue('id');
                            $id_lang  = (int)Tools::getValue('id_lang');
                            $id_shop    = (Tools::getIsset('id_shop')) ? (int)Tools::getValue('id_shop') : 1;
                            // echo json_encode($id_shop);
                            // die();
                            
                            $item     = new $module->menu_model($id_item,false, null, $id_shop);
                            // $langs    = $langs;
                            $lang   = new Language($id_lang);
                            
                            $json     = array();
                            if ($item) {
                                $json['title']['tab'.$lang->id] = array(
                                    'label' => $lang->name,
                                    'icon' => '<img src="../img/l/'.$lang->id.'.jpg">',
                                );
                                $json['content']['tab'.$lang->id] = $module->displayAjaxForm($item, $lang->id);
                                
                                // foreach($langs as $l):
                                    //   $json['title']['tab'.$l['id_lang']] = [
                                        //     'label' => $l['name'],
                                        //     'icon' => '<img src="../img/l/'.$l['id_lang'].'.jpg">'
                                        //   ];
                                        //   $json['content']['tab'.$l['id_lang']] = $module->displayAjaxForm($item,$l['id_lang']);
                                        // endforeach;
                                    }
                                    echo json_encode($json);
                                    die();
                                }
                                
                                if ($action == "updateItem") {
                                    $id_item = (int)Tools::getValue('id_item');
                                    $id_lang = (int)Tools::getValue('id_lang');
                                    $display_sections = (Tools::getIsset('display_sections') && (int)Tools::getValue('display_sections') == 1) ? true : false;
                                    $item     = new $module->menu_model($id_item, $id_lang);
                                    
                                    $item->label[$id_lang]  = stripslashes(pSQL((string)Tools::getValue('label')));
                                    $item->url[$id_lang]    = pSQL((string)Tools::getValue('url'));
                                    if ($item->id_parent == 0 && Tools::getIsset('silo')) {
                                        $item->id_silo = (int)Tools::getValue('silo');
                                    }
                                    
                                    $item->target           = pSQL((string)Tools::getValue('target'));
                                    $item->cssclass         = pSQL((string)Tools::getValue('cssclass'));
                                    $item->custom_parameter = pSQL((string)Tools::getValue('custom_parameter'));
                                    $item->display_sections[$id_lang] = $display_sections;
                                    
                                    if ($item->save()) {
                                        echo json_encode(array('success' => true));
                                    } else {
                                        echo json_encode(array('success' => false));
                                    }
                                    die();
                                }
                                
                                

                                
                                ###### TRY TO HACK
                                
                                if ($action == null) {
                                    $output                 = array();
                                    $output['success']      = false;
                                    $output['type']         = 'danger';
                                    $output['message']      = $module->l('No action');
                                    echo Tools::jsonEncode($output);
                                    die();
                                }
                            } else {
                                $output                 = array();
                                $output['success']      = false;
                                $output['type']         = 'danger';
                                $output['message']      = $module->l('Token error');
                                echo Tools::jsonEncode($output);
                                die();
                            }
                            