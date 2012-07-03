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
<?php $this->Html->css('colorbox.min', null, array('inline'=>false)); ?>
<?php $this->Html->script('jquery.colorbox.min', array('inline'=>false)); ?>
<?php $this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index')); ?>
<?php $this->Html->addCrumb(h($album['Album']['name']), 
        array('controller'=>'albums', 'action'=>'view', h($album['Album']['id']))); ?>
<?php $this->Html->addCrumb(__('Colorbox')); ?>
<?php $url = $this->X2->photoUrl(); ?>
<?php $show_date = Configure::read('X2.Photo.Show_Photo_Date'); ?>
<?php
$this->start('left');
echo $this->element('navigation');
$this->end();
?>

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
        <?php
        $title = !empty($photos[$i]['Photo']['title']) ? $photos[$i]['Photo']['title'] : $photos[$i]['Photo']['name']; 
        $image_s_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                Configure::read('X2.Dir.S').'/'.$photos[$i]['Photo']['file_name']);
        $image_l_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                Configure::read('X2.Dir.L').'/'.$photos[$i]['Photo']['file_name']);
        echo $this->Html->link(
            $this->Html->image($image_s_url, 
                array('alt' => h($title), 'class'=>'thumbnail')
            ), 
            $image_l_url, 
            array('class' => 'gallery', 'escape' => false, 'title'=>h($title))
        );
        ?>
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
<script type="text/javascript">
$(function(){
    $('.gallery').colorbox({rel:'gallery', scalePhotos: true, maxWidth: '95%', maxHeight: '95%'});
});
</script>