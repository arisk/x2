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
<?php $this->Html->addCrumb(__('Photos')); ?>
<?php $url = $this->X2->photoUrl(); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<div class="row">
    <div class="span8">
        <h3><?php echo __('Identical Photos'); ?></h3>
    </div>
</div>
<?php if(count($photos) > 0): ?>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Title') ?></th>
            <th><?php echo __('Created') ?></th>
            <th><?php echo __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($photos as $photo): ?>
        <tr>
            <td>
                <div class="photo">
                    <?php
                    $image_url = h($url.$photo['Photo']['file_path'].'/'.
                            Configure::read('X2.Dir.XS').'/'.$photo['Photo']['file_name']);
                    echo $this->Html->image($image_url, 
                        array(
                            'alt' => h($photo['Photo']['title']),
                            'class' => 'thumbnail',
                            'url' => array('controller' => 'photos', 'action' => 'view', h($photo['Photo']['id']))
                        )
                    );
                    ?>
                </div>
            </td>
            <td><?php echo $this->Html->link($photo['Photo']['title'], 
                    array('controller'=>'photos', 'action'=>'view', h($photo['Photo']['id']))); ?>&nbsp;</td>
            <td><?php echo $this->Time->timeAgoInWords(h($photo['Photo']['created'])); ?>&nbsp;</td>
            <td class="actions">
                <?php if($photo['Photo']['published']): ?>
                    <?php echo $this->Form->postLink('<i class="icon-remove-circle">&nbsp;</i>'.__('Unpublish'), 
                        array('controller'=>'photos', 'action'=>'publish', h($photo['Photo']['id']), 1), 
                        array('escape'=>false, 'class'=>'btn btn-small btn-danger'), 
                        __('Are you sure you want to unpublish %s?', h($photo['Photo']['name']))); 
                    ?>
                <?php else: ?>
                    <?php echo $this->Form->postLink('<i class="icon-ok-circle">&nbsp;</i>'.__('Publish'), 
                        array('controller'=>'photos', 'action'=>'publish', h($photo['Photo']['id'])), 
                        array('escape'=>false, 'class'=>'btn btn-small btn-primary'), 
                        __('Are you sure you want to publish %s?', h($photo['Photo']['name']))); 
                    ?>
                <?php endif; ?>
                <a class="btn btn-small" href="<?php echo $this->Html->url(
                        array('controller'=>'photos', 'action'=>'view', h($photo['Photo']['id']))); ?>">
                    <i class="icon-search">&nbsp;</i><?php echo __('View'); ?>
                </a>
                <a class="btn btn-small" href="<?php echo $this->Html->url(
                        array('controller'=>'photos', 'action'=>'edit', h($photo['Photo']['id']))); ?>">
                    <i class="icon-edit">&nbsp;</i><?php echo __('Edit'); ?>
                </a>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>'.__('Delete'), 
                    array('controller'=>'photos', 'action'=>'delete', h($photo['Photo']['id'])), 
                    array('escape'=>false, 'class'=>'btn btn-small btn-danger'), 
                    __('Are you sure you want to delete %s?', h($photo['Photo']['name']))); 
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>