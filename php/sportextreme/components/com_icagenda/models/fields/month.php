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
 * @version 	3.6.0 2016-07-08
 * @since       3.6.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Month frontend search filter.
 */
class JFormFieldMonth extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	3.6.0
	 */
	protected $type = 'month';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	3.6.0
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array(
					'1' => JText::_('JANUARY'),
					'2' => JText::_('FEBRUARY'),
					'3' => JText::_('MARCH'),
					'4' => JText::_('APRIL'),
					'5' => JText::_('MAY'),
					'6' => JText::_('JUNE'),
					'7' => JText::_('JULY'),
					'8' => JText::_('AUGUST'),
					'9' => JText::_('SEPTEMBER'),
					'10' => JText::_('OCTOBER'),
					'11' => JText::_('NOVEMBER'),
					'12' => JText::_('DECEMBER'),
					);

		return $options;
	}
}
