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
<?php $this->Html->addCrumb(__('Settings'), array('action'=>'index')); ?>
<?php $this->Html->addCrumb(__('View Settings')); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<h2><?php echo __('Settings'); ?></h2>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id', __('id')); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('value'); ?></th>
            <th><?php echo $this->Paginator->sort('section'); ?></th>
            <th><?php echo $this->Paginator->sort('description'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($settings as $setting): ?>
        <tr>
            <td><?php echo h($setting['Setting']['id']); ?>&nbsp;</td>
            <td><?php echo h($setting['Setting']['name']); ?>&nbsp;</td>
            <td><?php echo h($setting['Setting']['value']); ?>&nbsp;</td>
            <td><?php echo h($setting['Setting']['section']); ?>&nbsp;</td>
            <td><?php echo h($setting['Setting']['description']); ?>&nbsp;</td>
            <td><?php echo h($setting['Setting']['modified']); ?>&nbsp;</td>
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