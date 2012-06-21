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
            <th><?php echo $this->Paginator->sort('admin'); ?></th>
            <th><?php echo $this->Paginator->sort('active'); ?></th>
            <th><?php echo $this->Paginator->sort('last_login'); ?></th>
            <th><?php echo __('Actions'); ?></th>   
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
            <td>
                <?php if($user['User']['admin']): ?>
                    <span class="badge badge-success"><?php echo __('Yes'); ?></span>
                <?php else: ?>
                    <span class="badge badge-info"><?php echo __('No'); ?></span>
                <?php endif; ?>
            </td>
            <td>
                <?php if($user['User']['admin']): ?>
                    <?php if($user['User']['active']): ?>
                        <span class="badge badge-success"><?php echo __('Yes'); ?></span>
                    <?php else: ?>
                        <span class="badge badge-warning"><?php echo __('No'); ?></span>
                    <?php endif; ?>
                <?php elseif($user['User']['active']): ?>
                    <?php echo $this->Form->postLink('<i class="icon-user">&nbsp;</i>'.__('Deactivate'), 
                        array('controller'=>'users', 'action'=>'activate', h($user['User']['id']), 1), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-danger'), 
                        __('Are you sure you want to deactivate %s?', h($user['User']['username']))); 
                    ?>
                <?php else: ?>
                    <?php echo $this->Form->postLink('<i class="icon-user">&nbsp;</i>'.__('Activate'), 
                        array('controller'=>'users', 'action'=>'activate', h($user['User']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-primary'), 
                        __('Are you sure you want to activate %s?', h($user['User']['username']))); 
                    ?>
                <?php endif; ?>
            </td>
            <td><?php echo h($user['User']['last_login']); ?>&nbsp;</td>
            <td class="actions">
                <a class="btn" href="<?php echo $this->Html->url(array('action'=>'view', h($user['User']['id']))); ?>">
                    <i class="icon-search">&nbsp;</i><?php echo __('View'); ?>
                </a>
                <a class="btn" href="<?php echo $this->Html->url(array('action'=>'edit', h($user['User']['id']))); ?>">
                    <i class="icon-edit">&nbsp;</i><?php echo __('Edit'); ?>
                </a>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>'.__('Delete'), 
                        array('action' => 'delete', h($user['User']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-danger'), 
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