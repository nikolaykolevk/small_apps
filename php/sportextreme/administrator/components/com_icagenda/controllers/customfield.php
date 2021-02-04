<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2019 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.6.0 2015-12-03
 * @since       3.4.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.controllerform');

/**
 * Custom Field controller class.
 */
class iCagendaControllerCustomfield extends JControllerForm
{
    function __construct()
    {
        $this->view_list = 'customfields';
        parent::__construct();
    }

	/**
	 * Return Ajax to save a new custom field group
	 *
	 * @since 3.6.0
	 */
	function newGroup()
	{
		icagendaAjaxFilter::saveCustomFieldGroup();
	}

	/**
	 * Return Ajax to check if a custom field group is set to any custom field
	 *
	 * @since 3.6.0
	 */
	function checkGroup()
	{
		icagendaAjaxFilter::checkCustomFieldGroup();
	}

	/**
	 * Return Ajax to delete a custom field group
	 *
	 * @since 3.6.0
	 */
	function deleteGroup()
	{
		icagendaAjaxFilter::deleteCustomFieldGroup();
	}
}
