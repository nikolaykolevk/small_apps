<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-11-20
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

$app = JFactory::getApplication();

$actions = $app->getUserState('com_icagenda.registration.actions', '');

if ($actions)
{
	$layout = new JLayoutFile($actions, $basePath = JPATH_PLUGINS . '/icagenda/' . $actions . '/layouts');
	$displayData = array('item' => $this->item, 'actions' => $this->actions, 'params' => $this->params);
	$actionsHTML = $layout->render($displayData);
}
else
{
	$actionsHTML = 'No Action';
}

?>
<div id="icagenda" class="ic-actions-view<?php echo $this->pageclass_sfx; ?>">
	<?php echo $actionsHTML; ?>
	<div>
		<a href="index.php" class="btn btn-small btn-info button">
		<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
			<i class="icon-home icon-white"></i>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
		<?php else : ?>
			<span style="color:#FFF"><?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?></span>
		<?php endif; ?>
		</a>
	</div>
	<br />
</div>
<?php
if (version_compare(JVERSION, '3.0', 'lt'))
{
	JHtml::_('stylesheet', 'icagenda-front.j25.css', 'components/com_icagenda/add/css/');
}
