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
 * @version     3.6.9 2017-07-04
 * @since       1.2.3
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

// Test if translation is missing, set to en-GB by default
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

JHtml::stylesheet('com_icagenda/icagenda-back.css', false, true);


class JFormFieldTitleImg extends JFormField
{
	protected $type = 'TitleImg';

	protected function getLabel()
	{
		return ' ';
	}

	protected function getInput()
	{
		$html = array();

		// Affichage texte

		$label = $this->element['label'];
		$label = $this->translateLabel ? JText::_($label) : $label;

		$style = $this->element['style'];
		$style = $this->translateLabel ? JText::_($style) : $style;

		$class = $this->element['class'];
		$class = $this->translateLabel ? JText::_($class) : $class;

		$icimage = $this->element['icimage'];
		$image = '../media/com_icagenda/images/'. $icimage .'';

		$icicon = $this->element['icicon'];

		// Contruction
		$html[] = '<div class="';
		$html[] = $class;
		$html[] = ' element-title-img" ';
		$html[] = 'style="';
		$html[] = $style;
		$html[] = '">';

		if ($icimage)
		{
			$html[] = '<img src="';
			$html[] = $image;
			$html[] = '" />';
		}
		elseif ($icicon)
		{
			$html[] = '<span class="iCicon-';
			$html[] = $icicon;
			$html[] = '"></span> ';
		}

		$html[] = $label;
		$html[] = '</div>';

		return implode('',$html);
	}
}
