<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $this->extend('/Layouts/one'); ?>
<?php $this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index')); ?>
<?php $this->Html->addCrumb(h($photo['Album']['name']), 
        array('controller'=>'albums', 'action'=>'view', h($photo['Album']['id']))); ?>
<?php $this->Html->addCrumb(h($photo['Photo']['title']), 
        array('controller'=>'photos', 'action'=>'view', h($photo['Photo']['id']))); ?>
<?php $this->Html->addCrumb(__('Details')); ?>
<?php $url = $this->X2->photoUrl(); ?>
<div id="photo-top" class="row">
    <div class="span5">
        <div class="btn-toolbar">
            <div class="btn-group">
                <?php if(isset($neighbors['prev']['Photo']['id']) && !empty($neighbors['prev']['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'details', $neighbors['prev']['Photo']['id'])); ?>">
                        <i class="icon-backward"></i>
                        <?php echo __('Previous'); ?>
                    </a>
                <?php endif; ?>
                <?php if(isset($neighbors['next']['Photo']['id']) && !empty($neighbors['next']['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'details', $neighbors['next']['Photo']['id'])); ?>">
                        <?php echo __('Next'); ?>
                        <i class="icon-forward"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php if(Configure::read('X2.Photo.Allow_Downloads')): ?>
            <div class="btn-group">
                <a class="btn btn-small btn-success" href="<?php echo $this->Html->url(
                        array('action'=>'full', h($photo['Photo']['id']), 1)); ?>">
                    <i class="icon-download-alt">&nbsp;</i><?php echo __('Download'); ?>
                </a>
                <?php if(isset($photo['Metadata']) && !empty($photo['Metadata']['data'])): ?>
                <?php echo $this->Form->postLink('<i class="icon-remove-sign"></i>'.__('Remove EXIF'), 
                array('controller'=>'metadata', 'action'=>'delete', h($photo['Photo']['id'])), 
                array('escape'=>false, 'class'=>'btn btn-small btn-danger', 'title'=>__('Remove EXIF Metadata')), 
                        __('Are you sure you want to delete the EXIF data for this photo?')); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="span7">
        <h3>
            <?php if(isset($photo['Photo']['title'])): ?>
                <?php echo h($photo['Photo']['title']); ?>
            <?php else: ?>
                <?php echo h($photo['Photo']['name']); ?>
            <?php endif; ?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="span3">
        <div class="well">
            <h3><?php echo __('Thumbnail'); ?></h3>
            <ul class="thumbnails ma" style="max-width: 180px">
                <?php
                $image_url = $url.h($photo['Photo']['file_path'].'/'.Configure::read('X2.Dir.S').'/'.$photo['Photo']['file_name']);
                $alt = isset($photo['Photo']['title']) ? h($photo['Photo']['title']) : h($photo['Photo']['name']);
                ?>
                <li style="margin: 0;">
                    <div class="thumbnail ma">
                        <img src="<?php echo $image_url ?>" alt="<?php echo $alt ?>" />
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="span3">
        <div class="well">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>
                            <?php echo __('Photo'); ?>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo __('Name') ?></td>
                        <td><?php echo $this->Html->link($photo['Photo']['name'], 
                                array('action' => 'view', h($photo['Photo']['id']))); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Title') ?></td>
                        <td><?php echo $this->Html->link($photo['Photo']['title'], 
                                array('action' => 'view', h($photo['Photo']['id']))); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Album') ?></td>
                        <td>
                            <?php echo $this->Html->link($photo['Album']['name'], 
                                array('controller' => 'albums', 'action' => 'view', h($photo['Album']['id']))); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('Type') ?></td>
                        <td><?php echo h($photo['Photo']['type']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Size') ?></td>
                        <td><?php echo h($photo['Photo']['size']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Width') ?></td>
                        <td><?php echo h($photo['Photo']['width']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Height') ?></td>
                        <td><?php echo h($photo['Photo']['height']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Views') ?></td>
                        <td><?php echo h($photo['Photo']['views']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Date Taken') ?></td>
                        <td><?php echo h($photo['Photo']['taken']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Location') ?></td>
                        <td><?php echo h($photo['Photo']['location']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Last Viewed') ?></td>
                        <td><?php echo $this->Time->timeAgoInWords(h($photo['Photo']['last_viewed'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Created') ?></td>
                        <td><?php echo $this->Time->timeAgoInWords(h($photo['Photo']['created'])); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span6">
    <?php if(isset($photo['Metadata']) && !empty($photo['Metadata']['data'])): ?>
        <?php $metadata = unserialize($photo['Metadata']['data']); ?>
        <?php foreach($metadata as $m => $v): ?>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th><?php echo h($m); ?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($v as $key => $value): ?>
                <tr>
                    <td><?php echo h(preg_replace('/(?=[A-Z])/', ' ', $key)); ?></td>
                    <td>
                        <?php if(is_array($value)): ?>
                            <?php 
                            echo '<pre>';
                            print_r($value);
                            echo '</pre>'; 
                            ?>
                        <?php else: ?>
                            <?php echo h($value); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>