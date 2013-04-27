<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

?>

<form action="<?php //echo JRoute::_('index.php?option=com_bugs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="bug-form" class="form-validate">
	<div class="row-fluid">
		<div class="span7 form-horizontal">

			<fieldset>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('priority'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('priority'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('summary'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('summary'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('test_instructions'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('test_instructions'); ?></div>
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>

				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>

			</fieldset>
		</div>

	<div class="span4">
	<h4>Versions</h4>
		<hr>
		<fieldset class="form-vertical">
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version_server'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version_server', null, $this->data['version_server']); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version_php'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version_php', null, $this->data['version_php']); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version_mysql'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version_mysql', null, $this->data['version_mysql']); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version_joomla'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version_joomla', null, $this->data['version_joomla']); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version_browser'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version_browser', null, $this->data['version_browser']); ?></div>
			</div>

		</fieldset>
	</div>

</form>

