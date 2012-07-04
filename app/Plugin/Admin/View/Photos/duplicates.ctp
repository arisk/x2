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
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>

<div class="row">
    <div class="span5">
        <h3><?php echo __('Duplicate Photos'); ?></h3>
    </div>
    <div class="span3 sorter">
        <?php echo __('Sort'); ?>:
        <?php echo $this->Paginator->sort('taken', __('Date Taken'), array('class' => 'btn btn-mini')); ?> 
        <?php echo $this->Paginator->sort('views', null, array('class' => 'btn btn-mini')); ?>        
    </div>
</div>
<?php if(count($duplicates) > 0): ?>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id', __('id')); ?></th>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($duplicates as $photo): ?>
        <tr>
            <td>
                <div class="photo">
                    <?php
                    $image_url = h($this->X2->photoUrl().$photo['Photo']['file_path'].'/'.
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
                <a class="btn btn-small btn-primary" href="<?php echo $this->Html->url(
                        array('controller'=>'photos', 'action'=>'identical', h($photo['Photo']['hash']))); ?>">
                    <i class="icon-search">&nbsp;</i><?php echo __('View Duplicates'); ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
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
<?php endif; ?>