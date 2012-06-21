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
<?php echo $this->element('album_menu'); ?>
<?php $this->end(); ?>
<h2><?php echo __('Albums'); ?></h2>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id', __('id')); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('permission_id'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo __('Actions'); ?></th>            
        </tr>
    </thead>
    <tbody>
    <?php foreach ($albums as $album): ?>
        <tr>
            <td><?php echo h($album['Album']['id']); ?>&nbsp;</td>
            <td><?php echo $this->Html->link(($album['Album']['name']), 
                    array('action'=>'view', h($album['Album']['id']))); ?></td>
            <td><?php echo h($album['Perm']['name']); ?>&nbsp;</td>
            <td><?php echo h($this->Time->timeAgoInWords(h($album['Album']['created']))); ?>&nbsp;</td>
            <td class="actions">
                <a class="btn btn-small btn-primary" href="<?php echo $this->Html->url(
                        array('controller'=>'photos', 'action'=>'upload', h($album['Album']['id']))); ?>">
                    <i class="icon-upload">&nbsp;</i><?php echo __('Upload'); ?>
                </a>
                <div class="modal" id="album-<?php echo h($album['Album']['id']); ?>-modal" style="display: none;">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">×</button>
                        <h3><?php echo __('Album Integration Link'); ?></h3>
                    </div>
                    <div class="modal-body">
                        <h4><?php echo __('Slideshow View'); ?></h4>
                        <?php if(!empty($album['Photo'][0]['id'])): ?>
                        <?php $url = $this->Html->url(array(
                            'plugin'=>null, 'controller'=>'photos', 'action'=>'slideshow', 
                            h($album['Photo'][0]['id']), 't'=>'embedded'), true); ?>
                        <a href="<?php echo $url; ?>"><?php echo $url; ?></a>
                        <?php else: ?>
                            <?php echo __('You need at least one photo in your album for a slideshow'); ?>
                        <?php endif; ?>
                        <h4><?php echo __('Colorbox View'); ?></h4>
                        <?php $url = $this->Html->url(array(
                            'plugin'=>null, 'controller'=>'photos', 'action'=>'colorbox', 
                            h($album['Album']['id']), 't'=>'embedded'), true); ?>
                        <a href="<?php echo $url; ?>"><?php echo $url; ?></a>
                        <h4><?php echo __('Album View'); ?></h4>
                        <?php $url = $this->Html->url(array(
                            'plugin'=>null, 'controller'=>'albums', 'action'=>'view', 
                            h($album['Album']['id']), 't'=>'embedded'), true); ?>
                        <a href="<?php echo $url; ?>"><?php echo $url; ?></a>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn" data-dismiss="modal"><?php echo __('Close'); ?></a>
                    </div>
                </div>
                <a class="btn btn-small btn-info" data-toggle="modal" href="#album-<?php echo h($album['Album']['id']); ?>-modal" >
                    <?php echo __('Get Links'); ?>
                </a>
                <a class="btn btn-small" href="<?php echo $this->Html->url(array('action'=>'view', h($album['Album']['id']))); ?>">
                    <i class="icon-search">&nbsp;</i><?php echo __('View'); ?>
                </a>
                <a class="btn btn-small" href="<?php echo $this->Html->url(array('action'=>'edit', h($album['Album']['id']))); ?>">
                    <i class="icon-edit">&nbsp;</i><?php echo __('Edit'); ?>
                </a>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>'.__('Delete'), 
                    array('controller'=>'albums', 'action' => 'delete', h($album['Album']['id'])), 
                    array('escape'=>false, 'class'=>'btn btn-small btn-danger'), 
                    __('Are you sure you want to delete %s?', h($album['Album']['name']))); 
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