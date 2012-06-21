<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $this->extend('/Layouts/login'); ?>
<?php $this->Html->addCrumb(__('Register')); ?>
<div class="well well-large">
    <?php echo $this->Form->create('User', array('class'=>'form')); ?>
    <fieldset>
        <legend><?php echo __('Register'); ?></legend>
        <?php
        echo $this->Form->input('username');
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('confirmPassword', array('type'=>'password'));
        echo $this->Form->input('nickname');
        //echo $this->Form->input('locale');
        ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Register'), array('class'=>'btn btn-primary')) ?>
    <?php echo $this->Form->end(); ?>
</div>