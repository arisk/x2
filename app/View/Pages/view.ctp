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
<?php $this->Html->addCrumb(__('Content'), array('action'=>'index')); ?>
<?php $this->Html->addCrumb(h($page['Page']['title'])); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<div class="well well-small">
    <h3><?php echo __('Recent Pages'); ?></h3>
    <ul>
    <?php foreach($recent as $p): ?>
        <li><?php echo $this->Html->link($p['Page']['title'], array('action'=>'view', h($p['Page']['slug']))); ?></li>
    <?php endforeach; ?>
    </ul>
    <?php echo $this->Html->link(__('More'), array('action'=>'index')); ?>
</div>
<?php $this->end(); ?>
<div class="well well-small">
    <h3><?php echo h($page['Page']['title']); ?></h3>
    <div class="text">
        <?php echo $page['Page']['html']; ?>
    </div>
</div>