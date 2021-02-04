<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-05-01
 *
 * @package     iCagenda.Site
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Category frontend search filter.
 */
class JFormFieldCategories extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.6.0
	 */
	protected $type = 'categories';

	/**
	 * Method to get the field options.
	 *
	 * @return  array   The field option objects.
	 * @since   3.6.0
	 */
	public function getOptions()
	{
		$app            = JFactory::getApplication();
		$params         = $app->getParams();

		$filters_mode   = ($app->getMenu()->getActive()->params->get('search_filters') == 1)
						? $params->get('filters_mode', 1)
						: JComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);

		// Initialize variables.
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS catid, title AS cattitle');
		$query->from('#__icagenda_category AS c');
		$query->where('state = 1');

		// Search in menu filtered list
		if ($filters_mode == 1)
		{
			$mcatid = $params->get('mcatid', '');
			JArrayHelper::toInteger($mcatid);
			$list_catid = implode(',', $mcatid);

			if ($mcatid
				&& count($mcatid) > 0
				&& ! in_array('0', $mcatid))
			{
				$query->where('c.id IN (' . $list_catid . ')');
			}
		}

		$query->order('c.title');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			throw new Exception($db->getErrorMsg(), 500);
		}

		return $options;
	}
}
