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
$this->Html->addCrumb(__('Edit Photo'));
$this->Html->script('chosen.jquery.min', array('inline'=>false));
$this->Html->css('chosen.min', null, array('inline'=>false));
?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<div class="well well-small">
    <h3><?php echo __('Thumbnail'); ?></h3>
    <div class="photo">
        <?php
        $image_url = h('/'.Configure::read('X2.Dir.P').'/'.$photo['Photo']['file_path'].'/'.
                Configure::read('X2.Dir.S').'/'.$photo['Photo']['file_name']);
        print $this->Html->link($this->Html->image($image_url, 
            array('alt' => h($photo['Photo']['title']))), 
            array('controller' => 'photos', 'action' => 'view', h($photo['Photo']['id'])), 
            array('class' => 'thumbnail', 'escape' => false)
        );
        ?>
    </div>
    <div class="clearfix"></div>
    <br />
    <?php if(isset($photo['Metadata']) && !empty($photo['Metadata']['id'])): ?>
    <?php echo $this->Form->postLink('<i class="icon-remove-sign"></i>'.__('Remove EXIF'), 
    array('controller'=>'metadata', 'action'=>'delete', h($photo['Photo']['id'])), 
    array('escape'=>false, 'class'=>'btn btn-small btn-danger', 'title'=>__('Remove EXIF Metadata')), 
            __('Are you sure you want to delete the EXIF data for this photo?')); ?>
    <?php endif; ?>
</div>
<?php $this->end(); ?>
<div class="well well-large">
<?php echo $this->Form->create('Photo', array('id'=>'album-add-form', 'class'=>'form')); ?>
    <fieldset>
        <legend><?php echo __('Edit Photo'); ?></legend>
        <?php echo $this->Form->input('name'); ?>
        <?php echo $this->Form->input('title'); ?>
        <?php
        echo $this->Form->input('album_id', array('data-placeholder'=>__('Album')))
        ?>
        <?php echo $this->Form->input('views'); ?>
        <?php echo $this->Form->input('taken', array('type'=>'datetime')); ?>
        <?php echo $this->Form->input('location'); ?>
        <?php echo $this->Form->input('slug', array('id'=>'album-add-slug')); ?>
    </fieldset>
    <br />
    <?php echo $this->Form->submit(__('Save'), array('class'=>'btn btn-primary')) ?>
<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(function () {
    $('#AlbumAlbumId').chosen();
});
</script>