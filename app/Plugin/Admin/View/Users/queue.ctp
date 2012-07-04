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
<?php $this->Html->addCrumb(__('Users'), array('action'=>'index')); ?>
<?php $this->Html->addCrumb(__('Approval Queue')); ?>
<?php
$this->start('left');
echo $this->element('admin_navigation');
echo $this->element('user_menu');
$this->end();
?>
<h2><?php echo __('Users'); ?></h2>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id', __('id')); ?></th>
            <th><?php echo $this->Paginator->sort('username'); ?></th>
            <th><?php echo $this->Paginator->sort('email'); ?></th>
            <th><?php echo $this->Paginator->sort('nickname'); ?></th>
            <th><?php echo __('Actions'); ?></th>  
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['nickname']); ?>&nbsp;</td>
            <td class="actions">
                <?php echo $this->Form->postLink('<i class="icon-ok">&nbsp;</i>'.__('Approve'), 
                    array('action' => 'approve', h($user['User']['id'])), 
                    array('escape'=>false, 'class'=>'btn btn-small btn-primary'), 
                    __('Are you sure you want to approve %s?', h($user['User']['username']))); 
                ?>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>'.__('Delete'), 
                    array('action' => 'approve', h($user['User']['id'])), 
                    array('escape'=>false, 'class'=>'btn btn-small btn-danger'), 
                    __('Are you sure you want to delete %s?', h($user['User']['username']))); 
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p>
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
</p>
<div class="paging">
    <?php
    echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
    echo $this->Paginator->numbers(array('separator' => ''));
    echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
</div>