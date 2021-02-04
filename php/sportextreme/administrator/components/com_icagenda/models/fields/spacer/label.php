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
 * Spacer Label form field
 */
class JFormFieldSpacer_Label extends JFormField
{
	public $type = 'spacer_label';

	public function getLabel()
	{
		if (empty($this->element['label']))
		{
			return '';
		}

		$title  = $this->element['label']
				? (string) $this->element['label']
				: ($this->element['title'] ? (string) $this->element['title'] : '');

		$spacer_class = $this->class ? (string) $this->class : '';
		$class_ex     = explode(' ', $spacer_class);

		$heading = 'h4';

		foreach ($class_ex AS $c)
		{
			if (in_array(strtolower($c), array('h1', 'h2', 'h3', 'h4', 'h5', 'h6')))
			{
				$heading = strtolower($c);
			}
		}

		$class = trim(str_replace($heading, '', $spacer_class));
		$class = ! empty($class) ? ' class="' . $class . '"' : '';

		$html   = array();

		$html[] = '<div' . $class . '>';
		$html[] = ! empty($title) ? '<' . $heading . '>' . JText::_($title) . '</' . $heading . '>' : '';
		$html[] = '</div>';

		return '</div><div>' . implode('', $html);
	}

	public function getInput()
	{
		return '';
	}
}
