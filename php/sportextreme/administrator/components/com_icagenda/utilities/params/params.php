<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-30
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
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

/**
 * class icagendaParams
 */
class icagendaParams
{
	/**
	 * Function to encrypt user pro password
	 *
	 * @access  public static
	 * @param   $id - id of the event
	 * @return  list array of access levels, approval and event access status
	 *
	 * @since   3.4.0
	 */
	static public function encryptPassword()
	{
		$params = JComponentHelper::getParams( 'com_icagenda' );
		$icsys  = $params->get('icsys', 'core');

		if ($icsys == 'pro')
		{
			jimport('joomla.user.helper');

			$crypt1  = JUserHelper::genRandomPassword(2);
			$crypt2  = JUserHelper::genRandomPassword(2);
			$salt_8  = JUserHelper::genRandomPassword(8);
			$salt_16 = JUserHelper::genRandomPassword(16);
			$salt_32 = JUserHelper::genRandomPassword(32);

			$password = $params->get('password', '');

			$is_crypted = substr_count($password, '$');

			if ($is_crypted != 3 && strlen($password) != 0)
			{
				$encoded = base64_encode($password); // Encode Password Data Storage

				if (strlen($encoded) > 32)
				{
					$salt1 = $salt_16;
					$salt2 = $salt_8;
				}
				elseif (strlen($encoded) < 32 && strlen($encoded) > 16)
				{
					$salt1 = $salt_16;
					$salt2 = $salt_8;
				}
				else
				{
					$salt1 = $salt_32;
					$salt2 = $salt_16;
				}

				$pass_encoded = '$' . $crypt1 . '$' . $crypt2 . '$' . $salt1 . '.' . $encoded . '/' . $salt2;
				$password     = $pass_encoded;

				// Get the params and set the new values
				$params->set('password', $password);

				// Get a new database query instance
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);

				// Build the query
				$query->update('#__extensions AS a');
				$query->set('a.params = ' . $db->quote((string)$params));
				$query->where('a.element = "com_icagenda"');

				// Execute the query
				$db->setQuery($query);
				$db->query();
			}
		}
	}
}
