<?php
/**
 *------------------------------------------------------------------------------
 *  iC Library - Library by Jooml!C, for Joomla!
 *------------------------------------------------------------------------------
 * @package     iC Library
 * @subpackage  Form Rules
 * @copyright   Copyright (c)2013-2019 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        https://www.joomlic.com
 *
 * @version     1.4.4 2017-06-06
 * @since       1.4.4
 *------------------------------------------------------------------------------
*/

defined('JPATH_PLATFORM') or die;

if (version_compare(JVERSION, '3.0', 'lt'))
{
	jimport('joomla.registry.registry');
}

use Joomla\Registry\Registry;

/**
 * Form Rule class for iCagenda.
 *
 * Phone number validation (allows 6 digits as minimum against 7 of Joomla api).
 */
class iCFormRuleTel extends JFormRule
{
	/**
	 * Method to test the url for a valid parts.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   Registry          $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   JForm             $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   11.1
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, JForm $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

		if (!$required && empty($value))
		{
			return true;
		}

		/*
		 * @see http://www.nanpa.com/
		 * @see http://tools.ietf.org/html/rfc4933
		 * @see http://www.itu.int/rec/T-REC-E.164/en
		 *
		 * Regex by Steve Levithan
		 * @see http://blog.stevenlevithan.com/archives/validate-phone-number
		 * @note that valid ITU-T and EPP must begin with +.
		 */
		$regexarray = array(
			'NANP' => '/^(?:\+?1[-. ]?)?\(?([2-9][0-8][0-9])\)?[-. ]?([2-9][0-9]{2})[-. ]?([0-9]{4})$/',
			'ITU-T' => '/^\+(?:[0-9] ?){6,14}[0-9]$/',
			'EPP' => '/^\+[0-9]{1,3}\.[0-9]{4,14}(?:x.+)?$/',
		);

		if (isset($element['plan']))
		{
			$plan = (string) $element['plan'];

			if ($plan == 'northamerica' || $plan == 'us')
			{
				$plan = 'NANP';
			}
			elseif ($plan == 'International' || $plan == 'int' || $plan == 'missdn' || !$plan)
			{
				$plan = 'ITU-T';
			}
			elseif ($plan == 'IETF')
			{
				$plan = 'EPP';
			}

			$regex = $regexarray[$plan];

			// Test the value against the regular expression.
			if (preg_match($regex, $value) == false)
			{
				return false;
			}
		}
		else
		{
			/*
			 * If the rule is set but no plan is selected just check that there are between
			 * 7 and 15 digits inclusive and no illegal characters (but common number separators
			 * are allowed).
			 */
			$cleanvalue = preg_replace('/[+. \-(\)]/', '', $value);
			$regex = '/^[0-9]{6,15}?$/';

			if (preg_match($regex, $cleanvalue) == true)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		return true;
	}
}
