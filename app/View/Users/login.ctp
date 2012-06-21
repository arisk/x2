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
<?php $this->Html->addCrumb(__('Login')); ?>
<div class="well well-large">
    <?php echo $this->Form->create('User', array('class'=>'form')); ?>
    <fieldset>
        <legend><?php echo __('Login'); ?></legend>
        <?php
        echo $this->Form->input('username');
        echo $this->Form->input('password');
        ?>
    </fieldset>
    <p>
    <?php if(Configure::read('X2.User.Registration')): ?>
    <?php echo $this->Html->link(__('Register'), 
            array('controller'=>'users', 'action'=>'register'), 
            array('class'=>'btn btn-mini')); ?>
    <?php endif; ?>
    <?php echo $this->Html->link(__('Forgot Password'), 
            array('controller'=>'users', 'action'=>'forgot'), 
            array('class'=>'btn btn-mini')); ?>
    </p>
    <?php echo $this->Form->submit(__('Login'), array('class'=>'btn btn-primary')) ?>
    <?php echo $this->Form->end(); ?>
</div>