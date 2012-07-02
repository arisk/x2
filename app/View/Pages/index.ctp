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
$this->Html->script('chosen.jquery.min', array('inline'=>false));
$this->Html->css('chosen.min', null, array('inline'=>false));
?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<?php $this->end(); ?>
<p>
    <?php echo $this->Paginator->sort('created', null, array('class'=>'btn btn-mini'));?>
    <?php echo $this->Paginator->sort('modified', null, array('class'=>'btn btn-mini'));?>
</p>
<?php foreach($pages as $page): ?>
<div class="well well-small">
    <h3><?php echo h($page['Page']['title']); ?></h3>
    <div class="text">
        <?php echo String::truncate($page['Page']['html'], 200, array('exact' => false)); ?>
    </div>
    <p>
        <?php echo $this->Html->link(__('Read More'), array('action'=>'view', h($page['Page']['slug']))); ?>
    </p>
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