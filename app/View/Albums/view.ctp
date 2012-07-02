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
<?php $this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index')); ?>
<?php $this->Html->addCrumb(h($album['Album']['name'])); ?>
<?php $url = $this->X2->photoUrl(); ?>
<?php $this->start('left'); ?>
<div class="well">
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th>
                    <?php echo __('Album'); ?>
                </th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo __('Name') ?></td>
            <td><?php echo(h($album['Album']['name'])) ?></td>
        </tr>
        <tr>
            <td><?php echo __('Created') ?></td>
            <td><?php echo $this->Time->timeAgoInWords(h($album['Album']['created'])); ?></td>
        </tr>
        <?php if(isset($album['CoverPhoto']['id'])): ?>
            <tr>
                <td colspan="2">
                    <?php
                    $image_url = h($url.$album['CoverPhoto']['file_path'].'/'
                            .Configure::read('X2.Dir.S').'/'.$album['CoverPhoto']['file_name']);
                    ?>
                    <?php echo $this->Html->image($image_url, 
                            array('alt' => h($album['CoverPhoto']['title']), 'class' => 'thumbnail')) ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td colspan="2"><?php echo(h($album['Album']['description'])); ?></td>
        </tr>
        </tbody>
    </table>
    <?php if(!empty($album['ChildAlbum'])): ?>
        <h3><?php echo __('Child Albums'); ?></h3>
        <ul>
            <?php foreach($album['ChildAlbum'] as $childAlbum): ?>
                <li>
                    <?php echo $this->Html->link($childAlbum['name'], 
                            array('controller' => 'albums', 'action' => 'view', h($childAlbum['id'])));
                    ?>
                <?php endforeach; ?>
            </li>
        </ul>
    <?php endif; ?>
</div>
<?php echo $this->element('photo_menu'); ?>
<?php $this->end(); ?>
<div class="row">
    <div class="span5">
        <h3><?php echo __('Photos'); ?></h3>
    </div>
    <div class="span3 sorter">
        <?php echo __('Sort'); ?>:
        <?php echo $this->Paginator->sort('created', null, array('class' => 'btn btn-mini')); ?> 
        <?php echo $this->Paginator->sort('modified', null, array('class' => 'btn btn-mini')); ?>        
    </div>
</div>
<?php $count = count($photos); ?>
<?php for($i = 0; $i < $count; $i++): ?>
    <?php if($i % 4 == 0): ?>
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
            <p><small><?php echo $this->Time->nice(h($photos[$i]['Photo']['created'])); ?></small></p>
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