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
        $this->version = '1.0.0';
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
        Configuration::updateValue('SOLIDARITEGILETSJAUNES_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayTop');
    }

    public function uninstall()
    {
        Configuration::deleteByName('SOLIDARITEGILETSJAUNES_LIVE_MODE');

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
        if (((bool)Tools::isSubmit('submitSolidaritegiletsjaunesModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

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
        $helper->submit_action = 'submitSolidaritegiletsjaunesModule';
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
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'SOLIDARITEGILETSJAUNES_LIVE_MODE' => Configuration::get('SOLIDARITEGILETSJAUNES_LIVE_MODE', true),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
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
        $this->context->controller->addCSS($this->_path.'views/css/solidaritegiletsjaunes.css', 'all');
    }

    public function hookDisplayTop()
    {
        return $this->display(__FILE__, 'views/templates/hook/solidaritegiletsjaunes.tpl');
    }
}
