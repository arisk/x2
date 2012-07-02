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
<?php $url = $this->X2->photoUrl(); ?>
<?php
$this->start('left');
echo $this->element('navigation');
$this->end();
?>
<div class="row">
    <div class="span4">
        <h3><?php echo __('Albums'); ?></h3>
    </div>
    <div class="span4 sorter">
        <?php echo __('Sort'); ?>:
        <?php echo $this->Paginator->sort('name', null, array('class'=>'btn btn-mini'));?>
        <?php echo $this->Paginator->sort('created', null, array('class'=>'btn btn-mini'));?>
        <?php echo $this->Paginator->sort('modified', null, array('class'=>'btn btn-mini'));?>        
    </div>
</div>
<?php $count = count($albums); ?>
<?php for($i = 0; $i < $count; $i++): ?>
    <?php if($i % 4 == 0): ?>
    <ul class="row albums thumbnails">
    <?php endif; ?>
        <li class="span2">
            <?php if(empty($albums[$i]['CoverPhoto']['id'])): ?>
            <?php print $this->Html->link($this->Html->image('no-image.gif'), 
                    array('action'=>'view', $albums[$i]['Album']['id']), 
                    array('class'=>'thumbnail', 'escape'=>false)); ?>
            <?php else: ?>
            <?php $image_url = h($url.$albums[$i]['CoverPhoto']['file_path'].'/'.
                    Configure::read('X2.Dir.S').'/'.$albums[$i]['CoverPhoto']['file_name']); 
                  $alt = h($albums[$i]['CoverPhoto']['title']);
            ?>
            <?php 
            print $this->Html->image($image_url, 
                array(
                    'class'=>'thumbnail', 
                    'alt'=>$alt, 
                    'url'=>array('action'=>'view', h($albums[$i]['Album']['id'])),
                )
            ); 
            ?>           
            <?php endif; ?>
            <div class="caption">
            <?php echo $this->Html->link($albums[$i]['Album']['name'], array('action'=>'view', h($albums[$i]['Album']['id']))); ?>
                <p><small><?php echo $this->Time->nice(h($albums[$i]['Album']['created'])); ?></small></p>
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
    </div>
</div>