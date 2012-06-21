<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php
$this->extend('/Layouts/two');
$this->Html->addCrumb(__('Users'));
$this->Html->addCrumb(__('Edit Account'));
?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<?php $this->end(); ?>
<div class="well well-large">
<?php echo $this->Form->create('User', array('class'=>'form')); ?>
    <fieldset>
        <legend><?php echo __('Edit Account'); ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('nickname');
            //echo $this->Form->input('locale');
        ?>
    </fieldset>
    <p>
        <?php echo $this->Form->submit(__('Save'), array('class'=>'btn btn-primary')) ?>
    </p>
<?php echo $this->Form->end(); ?>
</div>