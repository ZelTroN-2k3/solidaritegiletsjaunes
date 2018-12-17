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
*
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
class Solidaritegiletsjaunes extends Module
{
    protected $config_form = false;
    public function __construct()
    {
        $this->name = 'solidaritegiletsjaunes';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'ZelTroN2k3';
        $this->need_instance = 1;
        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Solidarity Yellow Vests');
        $this->description = $this->l('Displays a small (yellow vest) top left front-office supporter yellow vests.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module Support Yellow Vests?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }
    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('D_HEADER', false);
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayTop');
    }
    public function uninstall()
    {
        Configuration::deleteByName('D_HEADER');
        return parent::uninstall();
    }
    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitTemplateModule')) == true) {
            $this->postProcess();
        }
        $this->context->smarty->assign('logo_path_ms', $this->_path.'logo.png');
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $output.$this->renderForm();
    }
    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTemplateModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getConfigForm()));
    }
    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Header'),
                        'hint' => $this->l('Display the Yellow Vests at the top of the header.'),
                        'name' => 'D_HEADER',
                        'desc' => $this->l('Display the Yellow Vests at the top of the header. This is the extreme top of the web page on the left of your shop.'),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }
    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'D_HEADER' => Configuration::get('D_HEADER', false),
        );
    }
    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key), True);
        }
    }
    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }
    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/solidaritegiletsjaunes.css');
        if (configuration::get('D_HEADER')== TRUE)
        {
            return $this->display(__FILE__, 'views/templates/hook/solidaritegiletsjaunes.tpl');
        }
    }
    public function hookDisplayTop()
    {
        return $this->hookDisplayHeader();
    }
}
