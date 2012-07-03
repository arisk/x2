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
<?php $show_date = Configure::read('X2.Photo.Show_Photo_Date'); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('navigation'); ?>
<?php $this->end(); ?>
<div class="row">
    <div class="span5">
        <h3><?php echo __('Photos'); ?></h3>
    </div>
    <?php if(Configure::read('X2.Photo.Show_Photo_Sort')): ?>
    <div class="span3 sorter">
        <?php echo __('Sort'); ?>:
        <?php echo $this->Paginator->sort('created', null, array('class' => 'btn btn-mini')); ?> 
        <?php echo $this->Paginator->sort('modified', null, array('class' => 'btn btn-mini')); ?>        
    </div>
    <?php endif; ?>
</div>
<?php $count = count($photos); ?>
<?php for($i = 0; $i < $count; $i++): ?>
        <?php if($i % 5 == 0): ?>
        <ul class="row photos thumbnails">
            <?php endif; ?>
        <li class="span2">
            <?php if(empty($photos[$i]['Photo']['file_name'])): ?>
                <?php
                print $this->Html->link($this->Html->image('no-image.gif'), 
                        array('action' => 'view', h($albums[$i]['Album']['id'])), 
                        array('class' => 'thumbnail', 'escape' => false));
                ?>
            <?php else: ?>
                <?php
                $image_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                        Configure::read('X2.Dir.S').'/'.$photos[$i]['Photo']['file_name']);
                print $this->Html->image($image_url, 
                    array(
                        'class'=>'thumbnail', 
                        'alt' => h($photos[$i]['Photo']['title']), 
                        'url'=>array('controller' => 'photos', 'action' => 'view', h($photos[$i]['Photo']['id'])),
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
                <?php if($show_date): ?>
                <p><small><?php echo $this->Time->nice(h($photos[$i]['Photo']['taken'])); ?></small></p>
                <?php endif; ?>
            </div>
        </li>
        <?php if($i % 5 == 4): ?>
        </ul>
    <?php endif; ?>
<?php endfor; ?>
<?php if(($i-1) % 5 != 4): ?>
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