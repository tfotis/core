<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Search_Controller_Admin class.
 */
class Search_Controller_Admin extends Zikula_Controller
{

    /**
     * The main administration function.
     *
     * This function is the default function, and is called whenever the
     * module is called without defining arguments.
     * As such it can be used for a number of things, but most commonly
     * it either just shows the module menu and returns or calls whatever
     * the module designer feels should be the default function (often this
     * is the view() function)
     *
     * @return string The main module admin page.
     */
    public function main()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Search::', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('search_admin_main.tpl');
    }

    /**
     * Modify configuration.
     *
     * This is a standard function to modify the configuration parameters of the module.
     *
     * @return string The configuration page.
     */
    public function modifyconfig()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Search::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // get the list of available plugins
        $plugins = ModUtil::apiFunc('Search', 'user', 'getallplugins', array('loadall' => true));

        // get the disabled status
        foreach ($plugins as $key => $plugin) {
            if (isset($plugin['title'])) {
                $plugins[$key]['disabled'] = $this->getVar("disable_$plugin[title]");
            }
        }

        $this->view->setCaching(false);

        // assign all module vars
        $this->view->assign($this->getVars());

        // assign the plugins
        $this->view->assign('plugins', $plugins);

        // Return the output that has been generated by this function
        return $this->view->fetch('search_admin_modifyconfig.tpl');
    }

    /**
     * Update the configuration.
     *
     * This is a standard function to update the configuration parameters of the
     * module given the information passed back by the modification form
     * Modify configuration.
     *
     * @return void
     */
    public function updateconfig()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Search::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code.
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Search', 'admin', 'main'));
        }

        // Update module variables.
        $itemsperpage = (int)FormUtil::getPassedValue('itemsperpage', 10, 'POST');
        $this->setVar('itemsperpage', $itemsperpage);
        $limitsummary = (int)FormUtil::getPassedValue('limitsummary', 255, 'POST');
        $this->setVar('limitsummary', $limitsummary);

        $disable = FormUtil::getPassedValue('disable', null, 'REQUEST');
        // get the list of available plugins
        $plugins = ModUtil::apiFunc('Search', 'user', 'getallplugins', array('loadall' => true));
        // loop round the plugins
        foreach ($plugins as $searchplugin) {
            // set the disabled flag
            if (isset($disable[$searchplugin['title']])) {
                $this->setVar("disable_$searchplugin[title]", true);
            } else {
                $this->setVar("disable_$searchplugin[title]", false);
            }
        }

        // the module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Done! Saved module configuration.'));

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('Search', 'admin', 'main'));
    }

}
