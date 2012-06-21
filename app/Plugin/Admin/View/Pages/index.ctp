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
<?php $this->Html->addCrumb(__('Albums')); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php echo $this->element('page_menu'); ?>
<?php $this->end(); ?>
<h2><?php echo __('Albums'); ?></h2>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id', __('id')); ?></th>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('published'); ?></th>
            <th><?php echo $this->Paginator->sort('protomoted'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th><?php echo __('Actions'); ?></th>            
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?php echo h($page['Page']['id']); ?>&nbsp;</td>
            <td><?php echo $this->Html->link($page['Page']['title'], 
                    array('action'=>'view', h($page['Page']['id']))); ?></td>
            <td>
                <?php if($page['Page']['published']): ?>
                    <?php echo $this->Form->postLink('<i class="icon-circle-arrow-down">&nbsp;</i>'.__('Unpublish'), 
                        array('controller'=>'pages', 'action'=>'publish', h($page['Page']['id']), 1), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-danger'), 
                        __('Are you sure you want to unpublish %s?', h($page['Page']['title']))); 
                    ?>
                <?php else: ?>
                    <?php echo $this->Form->postLink('<i class="icon-circle-arrow-up">&nbsp;</i>'.__('Publish'), 
                        array('controller'=>'pages', 'action'=>'publish', h($page['Page']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-primary'), 
                        __('Are you sure you want to activate %s?', h($page['Page']['title']))); 
                    ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if($page['Page']['promoted']): ?>
                    <?php echo $this->Form->postLink('<i class="icon-circle-arrow-down">&nbsp;</i>'.__('Un-Promote'), 
                        array('controller'=>'pages', 'action'=>'promote', h($page['Page']['id']), 1), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-danger'), 
                        __('Are you sure you want to un-promote %s from the front page?', h($page['Page']['title']))); 
                    ?>
                <?php else: ?>
                    <?php echo $this->Form->postLink('<i class="icon-circle-arrow-up">&nbsp;</i>'.__('Promote'), 
                        array('controller'=>'pages', 'action'=>'promote', h($page['Page']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-mini btn-primary'), 
                        __('Are you sure you want to promote %s to the front page?', h($page['Page']['title']))); 
                    ?>
                <?php endif; ?>
            </td>
            <td><?php echo $this->Time->timeAgoInWords(h($page['Page']['created'])); ?>&nbsp;</td>
            <td><?php echo $this->Time->timeAgoInWords(h($page['Page']['modified'])); ?>&nbsp;</td>
            <td class="actions">
                <a class="btn btn-small" href="<?php echo $this->Html->url(array('action'=>'view', h($page['Page']['id']))); ?>">
                    <i class="icon-search">&nbsp;</i><?php echo __('View'); ?>
                </a>
                <a class="btn btn-small" href="<?php echo $this->Html->url(array('action'=>'edit', h($page['Page']['id']))); ?>">
                    <i class="icon-edit">&nbsp;</i><?php echo __('Edit'); ?>
                </a>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>'.__('Delete'), 
                        array('action' => 'delete', h($page['Page']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-small btn-danger'), 
                        __('Are you sure you want to delete %s?', h($page['Page']['title']))); 
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