<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $this->extend('/Layouts/search'); ?>
<?php $this->Html->addCrumb(__('Photos'), array('controller'=>'photos', 'action'=>'index')); ?>
<?php $this->Html->addCrumb(__('Search')); ?>
<?php $url = $this->X2->photoUrl(); ?>
<?php
$this->Html->script('chosen.jquery.min', array('inline'=>false));
$this->Html->css('chosen.min', null, array('inline'=>false));
?>
<?php $this->start('left'); ?>
<div class="well well-small search">
    <?php
    echo $this->Form->create('Photo', array(
        'class'=>'form',
        'url' => array_merge(array('action' => 'search'), $this->params['pass'])
    ));?>
    <fieldset>
        <legend><?php echo __('Search'); ?></legend>
    <?php
    echo $this->Form->input('title');
    echo $this->Form->input('album_id', array('data-placeholder'=>__('Select an Album'), 'empty'=>''));
    echo $this->Form->input('username');
    ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Search'), array('class'=>'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?> 
</div>
<script type="text/javascript">
$(function(){
    $('#PhotoAlbumId').chosen();
})
</script>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<div class="row">
    <div class="span5">
        <h3><?php echo __('Photos'); ?></h3>
    </div>
    <div class="span3 sorter">
        <?php echo __('Sort'); ?>:
        <?php echo $this->Paginator->sort('taken', __('Date Taken'), array('class' => 'btn btn-mini')); ?> 
        <?php echo $this->Paginator->sort('views', null, array('class' => 'btn btn-mini')); ?>        
    </div>
</div>
<?php $count = count($photos); ?>
<?php for($i = 0; $i < $count; $i++): ?>
    <?php if($i % 4 == 0): ?>
    <ul class="row thumbnails">
        <?php endif; ?>
    <li class="span2">
        <?php if(empty($photos[$i]['Photo']['file_name'])): ?>
            <?php
            print $this->Html->link($this->Html->image('no-image.gif'), 
                    array('action' => 'view', h($albums[$i]['Album']['id'])), array('class' => 'thumbnail', 'escape' => false));
            ?>
        <?php else: ?>
            <?php
            $image_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                    Configure::read('X2.Dir.S').'/'.$photos[$i]['Photo']['file_name']);
            echo $this->Html->image($image_url, 
                array(
                    'alt' => h($photos[$i]['Photo']['title']),
                    'class' => 'thumbnail',
                    'url' => array('controller' => 'photos', 'action' => 'view', h($photos[$i]['Photo']['id']))
                )
            );
            ?>
        <?php endif; ?>
        <div class="caption">
            <?php
            $title = !empty($photos[$i]['Photo']['title']) ? $photos[$i]['Photo']['title'] : $photos[$i]['Photo']['name']; 
            ?>
            <?php echo $this->Html->link($title, 
                    array('controller'=>'photos', 'action'=>'view', h($photos[$i]['Photo']['id']))); ?>
            <p><small><?php echo $this->Time->nice(h($photos[$i]['Photo']['taken'])); ?></small></p>
            <p><small><?php echo h($photos[$i]['Photo']['views']).' '.__('views'); ?></small></p>
        </div>
    </li>
    <?php if($i % 4 == 3): ?>
    </ul>
    <?php endif; ?>
<?php endfor; ?>
<?php if(($i-1) % 4 != 3): ?>
    <?php echo '</ul>'; ?>
<?php endif; ?>

<div class="row">
    <div class="span8">
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
    </div>
</div>