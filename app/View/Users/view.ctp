<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $this->extend('/Layouts/two'); ?>
<?php $this->Html->addCrumb(__('Users')); ?>
<?php $this->Html->addCrumb(__('Your account')); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<?php $this->end(); ?>
<h2><?php echo __('User'); ?></h2>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo __('Field'); ?></th>
            <th><?php echo __('Value') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="key"><?php echo __('Username'); ?>:</td>
            <td><?php echo h($user['User']['username']); ?></td>
        </tr>
        <tr>
            <td class="key"><?php echo __('Email'); ?>:</td>
            <td><?php echo h($user['User']['email']); ?></td>
        </tr>
        <tr>
            <td class="key"><?php echo __('Nickname'); ?>:</td>
            <td><?php echo h($user['User']['nickname']); ?></td>
        </tr>
        <tr>
            <td class="key"><?php echo __('Last Login'); ?>:</td>
            <td><?php echo $this->Time->timeAgoInWords(h($user['User']['last_login'])); ?></td>
        </tr>
        <?php
        /*
        <tr>
            <td class="key"><?php echo __('Locale'); ?>:</td>
            <td><?php echo h($user['User']['locale']); ?></td>
        </tr>
        */
        ?>
    </tbody>
</table>