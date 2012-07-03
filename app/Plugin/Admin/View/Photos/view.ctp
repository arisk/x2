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
function dimensions($width, $height, $fit = null){
    $dx = 1024;
    $dy = 768;
    
    if($fit == null){
        $fit = 'inside';
    }

    $dim = array();
    if($fit == 'inside' || $fit == 'outside'){
        $rx = $width / $dx;
        $ry = $height / $dy;

        if($fit == 'inside'){
            $ratio = ($rx > $ry) ? $rx : $ry;
        }
        else{
            $ratio = ($rx < $ry) ? $rx : $ry;
        }

        $dim['width'] = round($width / $ratio);
        $dim['height'] = round($height / $ratio);
    }
    if($dim['width'] > $width){
        $dim['width'] = $width;
        $dim['height'] = $height;        
    }
    return $dim;
}
?>
<?php $this->extend('/Layouts/one'); ?>
<?php $this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index')); ?>
<?php $this->Html->addCrumb(h($photo['Album']['name']), 
        array('controller'=>'albums', 'action'=>'view', h($photo['Album']['id']))); ?>
<?php $this->Html->addCrumb(h($photo['Photo']['title'])); ?>
<?php $url = Router::url('/' . Configure::read('X2.Dir.P')); ?>
<div id="photo-top" class="row">
    <div class="span12">
        <div class="btn-toolbar">
            <div class="btn-group">
                <?php if(isset($neighbors['prev']['Photo']['id']) && !empty($neighbors['prev']['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'view', h($neighbors['prev']['Photo']['id']))); ?>">
                        <i class="icon-backward"></i>
                        <?php echo __('Prev'); ?>
                    </a>
                <?php endif; ?>
                <?php if(isset($neighbors['next']['Photo']['id']) && !empty($neighbors['next']['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'view', h($neighbors['next']['Photo']['id']))); ?>">
                        <?php echo __('Next'); ?>
                        <i class="icon-forward"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="btn-group">
                <?php if(isset($first['Photo']['id']) && !empty($first['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'view', h($first['Photo']['id']))); ?>">
                        <i class="icon-fast-backward"></i>
                        <?php echo __('First'); ?>
                    </a>
                <?php endif; ?>
                <?php if(isset($last['Photo']['id']) && !empty($last['Photo']['id'])): ?>
                    <a class="btn btn-small" href="<?php echo $this->Html->url(
                            array('action'=>'view', h($last['Photo']['id']))); ?>">
                        <?php echo __('Last'); ?>
                        <i class="icon-fast-forward"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="btn-group">
                <a class="btn btn-small btn-primary" href="<?php echo $this->Html->url(
                        array('action' => 'slideshow', h($photo['Photo']['id']))); ?>">
                    <i class="icon-play-circle"></i>
                    <?php echo __('Slideshow'); ?>
                </a>
                <a class="btn btn-small btn-primary" href="<?php echo $this->Html->url(
                        array('controller' => 'photos', 'action' => 'details', h($photo['Photo']['id']))); ?>">
                    <i class="icon-info-sign"></i>
                    <?php echo __('Details'); ?>
                </a>
                <a class="btn btn-small btn-primary" href="<?php echo $this->Html->url(
                        array('controller' => 'photos', 'action' => 'full', h($photo['Photo']['id']))); ?>">
                    <i class="icon-zoom-in"></i>
                    <?php echo __('Full Size'); ?>
                </a>
            </div>
            <div class="btn-group">
                <a class="btn btn-success" href="<?php echo $this->Html->url(
                        array('action'=>'cover', h($photo['Photo']['id']))); ?>" title="<?php echo __('Make Album Cover'); ?>" >
                        <i class="icon-screenshot">&nbsp;</i>
                </a>
                <a class="btn btn-success" href="<?php echo $this->Html->url(
                        array('action'=>'full', h($photo['Photo']['id']), true)); ?>" title="<?php echo __('Download'); ?>" >
                        <i class="icon-download-alt">&nbsp;</i>
                </a>
                <a class="btn btn-warning" href="<?php echo $this->Html->url(
                        array('action'=>'edit', h($photo['Photo']['id']))); ?>" title="<?php echo __('Edit'); ?>">
                        <i class="icon-edit">&nbsp;</i>
                </a>
                <a class="btn btn-warning" href="<?php echo $this->Html->url(
                        array('action'=>'crop', h($photo['Photo']['id']))); ?>" title="<?php echo __('Crop'); ?>">
                        <i class="icon-resize-small">&nbsp;</i>
                </a>
                <?php echo $this->Form->postLink('<i class="icon-trash">&nbsp;</i>', 
                array('action'=>'delete', h($photo['Photo']['id'])), 
                array('escape'=>false, 'class'=>'btn btn-danger', 'title'=>__('Delete Photo')), 
                        __('Are you sure you want to delete %s?', h($photo['Photo']['name']))); ?>
                <?php echo $this->Form->postLink('<i class="icon-hand-left">&nbsp;</i>', 
                array('action'=>'rotate', h($photo['Photo']['id']), 'cc'), 
                array('escape'=>false, 'class'=>'btn', 'title'=>__('Rotate left'))); ?>
                <?php echo $this->Form->postLink('<i class="icon-hand-right">&nbsp;</i>', 
                array('action'=>'rotate', h($photo['Photo']['id']), 'c'), 
                array('escape'=>false, 'class'=>'btn', 'title'=>__('Rotate right'))); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="span12">
        <h3 class="center">
            <?php if(isset($photo['Photo']['title'])): ?>
                <?php echo h($photo['Photo']['title']); ?>
            <?php else: ?>
                <?php echo h($photo['Photo']['name']); ?>
            <?php endif; ?>
        </h3>
    </div>
</div>
<?php if(!empty($photo['Photo']['description'])): ?>
<div class="row">
    <div class="span6 offset3">
        <?php echo $photo['Photo']['description']  ?>
    </div>
</div>
<?php endif; ?>
<?php
if(!empty($photo['Photo']['width']) && !empty($photo['Photo']['width'])){
    $dimensions = dimensions(h($photo['Photo']['width']), h($photo['Photo']['height']));
}
else{
    $dimensions = null;
}
?>
<ul class="thumbnails ma" <?php echo isset($dimensions['width']) ? 'style="max-width: '.($dimensions['width']+10).'px;" ' : ''; ?>>
    <?php
    $image_url = $url.'/'.h($photo['Photo']['file_path'].'/'.Configure::read('X2.Dir.L').'/'.$photo['Photo']['file_name']);
    if(isset($rotated) && $rotated){
        $image_url.= '?'.uniqid();
    }
    $alt = isset($photo['Photo']['title']) ? h($photo['Photo']['title']) : h($photo['Photo']['name']);
    ?>
    <li style="margin-left:0;">
        <div class="thumbnail">
            <img src="<?php echo $image_url ?>" alt="<?php echo $alt ?>" />
        </div>
    </li>
</ul>
<?php echo $this->Js->writeBuffer(); ?>