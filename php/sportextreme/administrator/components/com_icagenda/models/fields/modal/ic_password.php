<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-30
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.path');
jimport('joomla.form.formfield');

class JFormFieldModal_ic_password extends JFormField
{
	protected $type = 'modal_ic_password';

	protected function getInput()
	{
		$_pass = str_replace('/', '.', $this->value);
		$pass_ex = explode('.', $_pass);

		if (isset($pass_ex[1]))
		{
			$value = base64_decode($pass_ex[1]); // Decode password data stored
		}
		else
		{
			$value = $this->value;
		}

		$html = '<input type="password" id="' . $this->id . '" name="' . $this->name . '" value="' . $value . '" />';

		return $html;
	}
}
