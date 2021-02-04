<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-30
 *
 * @package     iCagenda.Site
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_SITE . '/helpers/iCicons.class.php');

/**
 * Controller class for iCagenda.
 */
class iCagendaController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$paramsC  = JComponentHelper::getParams('com_icagenda');
		$cache    = $paramsC->get('enable_cache', 0);
		$cachable = false;

		if ($cache == 1)
		{
			$cachable = true;
		}

		$document = JFactory::getDocument();

		$safeurlparams = array(
			'catid'  => 'INT',
			'id'     => 'INT',
			'date'   => 'STRING',
			'page'   => 'INT',
			'year'   => 'INT',
			'month'  => 'INT',
			'return' => 'BASE64',
			'print'  => 'BOOLEAN',
			'lang'   => 'CMD',
			'Itemid' => 'INT',
		);

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
