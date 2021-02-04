<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-12-01
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

function iCagendaBuildRoute( &$query )
{
	$segments = array();

	// @DEPRECATED (@since 3.6.0 - @removed 3.7.0): link event
	if (isset($query['layout']) && $query['layout'] == 'event')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id=' . (int) $query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'] . ':' . $alias;
		}

		$segments[] = $query['id'];

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}


	// Event view (since 3.6.0)
	if (isset($query['view']) && $query['view'] == 'event')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id=' . (int) $query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'] . ':' . $alias;
		}

		$segments[] = $query['id'];

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// link submit
	if (isset($query['layout']) && $query['layout'] == 'send')
	{
		$segments[] = 'sending';

		unset($query['view']);
		unset($query['layout']);
	}

	// Registration Cancel
	if (isset($query['layout']) && $query['layout'] == 'cancel')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id='.(int)$query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'].':'.$alias;
		}

		$segments[] = $query['id'];
		$segments[] = 'cancel';

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// Registration Actions
	if (isset($query['layout']) && $query['layout'] == 'actions')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id='.(int)$query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'].':'.$alias;
		}

		$segments[] = $query['id'];
		$segments[] = 'actions';

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// Registration Complete
	if (isset($query['layout']) && $query['layout'] == 'complete')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id='.(int)$query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'].':'.$alias;
		}

		$segments[] = $query['id'];
		$segments[] = 'complete';

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// @DEPRECATED (@since 3.6.0 - @removed 3.7.0): link registration
	if (isset($query['layout']) && $query['layout'] == 'registration')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id='.(int)$query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'].':'.$alias;
		}

		$segments[] = $query['id'];
		$segments[] = 'registration';

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// Registration view (since 3.6.0)
	if (isset($query['view']) && $query['view'] == 'registration')
	{
		// Make sure we have the id and the alias
		if (strpos($query['id'], ':') === false)
		{
			$db = JFactory::getDbo();
			$aquery = $db->setQuery($db->getQuery(true)
				->select('alias')
				->from('#__icagenda_events')
				->where('id=' . (int)$query['id'])
			);
			$alias = $db->loadResult();

			$query['id'] = $query['id'] . ':' . $alias;
		}

		$segments[] = $query['id'];

		// Additional alias to registration form (en-GB = registration)
		$add_alias = JText::_('COM_ICAGENDA_REGISTRATION_TITLE');

		if (JFactory::getConfig()->get('unicodeslugs') == 1)
		{
			$add_alias = JFilterOutput::stringURLUnicodeSlug($add_alias);
		}
		else
		{
			$add_alias = JFilterOutput::stringURLSafe($add_alias);
		}

		if (trim($add_alias) == '') $add_alias = JText::_('COM_ICAGENDA_REGISTRATION_TITLE');

		$segments[] = $add_alias;

		unset($query['id']);
		unset($query['view']);
		unset($query['layout']);
	}

	// Submit view
	if (isset($query['view']) && $query['view'] == 'submit')
	{
		$segments[] = 'submission';

		unset($query['view']);
		unset($query['layout']);
	}

	// List of events view
	if (isset($query['view']) && $query['view'] == 'list')
	{
		unset($query['view']);
		unset($query['layout']);
	}

	return $segments;
}

function iCagendaParseRoute( $segments )
{
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$item = $menu->getActive();
	$vars = array();

	// Count route segments
	$count = count($segments);

	// Routing submit an event to complete ('send' view) page
	if (in_array('sending', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'submit';
		$vars['layout'] = 'send';
	}

	// Registration Cancel
	elseif (in_array('cancel', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'registration';
		$vars['layout'] = 'cancel';
		$vars['id']     = $segments[0];
	}

	// Registration Actions
	elseif (in_array('actions', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'registration';
		$vars['layout'] = 'actions';
		$vars['id']     = $segments[0];
	}

	// Registration Complete
	elseif (in_array('complete', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'registration';
		$vars['layout'] = 'complete';
		$vars['id']     = $segments[0];
	}

	// Routing registration form (2 segments: ID, view)
	elseif ($count == '2')
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'registration';
		$vars['id']     = $segments[0];
	}

	// @DEPRECATED (@since 3.6.0 - @removed 3.7.0): Routing registration form (2 segment: ID, view)
	elseif (in_array('registration', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'list';
		$vars['layout'] = 'registration';
		$vars['id']     = $segments[0];
	}

	// Submit an event form
	elseif (in_array('submission', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'submit';
	}

	// List of events
	elseif ($count == '0')
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'list';
	}

	// New routing event details view (1 segment: ID)
	elseif ($count == '1')
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'event';
		$vars['id']     = $segments[0];
	}

	// @DEPRECATED (@since 3.6.0 - @removed 3.7.0) : Routing event details view (1 segment: ID)
	elseif (in_array('event', $segments))
	{
		$vars['option'] = 'com_icagenda';
		$vars['view']   = 'list';
		$vars['layout'] = 'event';
		$vars['id']     = $segments[0];
	}

	return $vars;
}
