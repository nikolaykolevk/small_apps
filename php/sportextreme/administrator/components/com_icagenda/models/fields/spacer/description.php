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
 * @version     3.6.0 2016-07-07
 * @since       3.6.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * Spacer Description form field
 */
class JFormFieldSpacer_Description extends JFormField
{
	public $type = 'spacer_description';

	public function getLabel()
	{
		if (empty($this->element['label']))
		{
			return '';
		}

		$desc   = $this->element['label']
				? (string) $this->element['label']
				: ($this->element['title'] ? (string) $this->element['title'] : '');

		$class  = $this->class ? (string) trim($this->class) : '';
		$class  = ! empty($class) ? ' class="' . $class . '"' : ' style="clear: both;"';

		$html   = array();

		$html[] = '<div' . $class . '>';
		$html[] = ! empty($desc) ? $desc : '';
		$html[] = '</div>';

		return '</div><div>' . implode('', $html);
	}

	public function getInput()
	{
		return '';
	}
}
