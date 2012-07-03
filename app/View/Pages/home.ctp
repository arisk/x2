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
?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<div class="well well-small">
    <h3><?php echo __('Content'); ?></h3>
    <ul>
    <?php foreach($recent as $page): ?>
        <li><?php echo $this->Html->link(h($page['Page']['title']), array('action'=>'view',h($page['Page']['slug']))); ?></li>
    <?php endforeach; ?>
    </ul>
    <?php echo $this->Html->link(__('More'), array('action'=>'index')); ?>
</div>
<?php $this->end(); ?>
<?php foreach($pages as $page): ?>
<div class="well well-small">
    <h3><?php echo h($page['Page']['title']); ?></h3>
    <div class="text">
        <?php echo $page['Page']['html']; ?>
    </div>
</div>
<?php endforeach; ?>
<?php if(Configure::read('X2.Pagination.Details')): ?>
<p>
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>	
</p>
<?php endif; ?>
<div class="paging">
    <?php
    echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
    echo $this->Paginator->numbers(array('separator' => ''));
    echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
</div>