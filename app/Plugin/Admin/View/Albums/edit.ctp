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
$this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index'));
$this->Html->addCrumb(__('Create Album'));
$this->Html->script('chosen.jquery.min', array('inline'=>false));
$this->Html->css('chosen.min', null, array('inline'=>false));
?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<div class="well well-large">
<?php echo $this->Form->create('Album', array('id'=>'album-add-form', 'class'=>'form')); ?>
    <fieldset>
        <legend><?php echo __('Edit Album'); ?></legend>
        <?php echo $this->Form->input('name'); ?>
        <?php echo $this->Form->input('permission_id'); ?>
        <?php 
        echo $this->Form->input('parent_id', array('data-placeholder'=>__('Parent Album'), 'empty'=>'Root Level'))
        ?>
        <?php echo $this->Form->input('description'); ?>
        <?php echo $this->Form->input('slug', array('id'=>'album-add-slug')); ?>
        <?php echo $this->Js->link(__('Generate Slug'), array('action'=>'slug'), 
                array(
                    'class'=>'btn btn-mini',
                    'method'=>'post',
                    'success'=>'$("#album-add-slug").val(data);',
                    'data'=>'$("#album-add-form").serialize()',
                    'buffer'=>false,
                    'dataExpression' => true,
                )
            ); ?>
    </fieldset>
    <br />
    <?php echo $this->Form->submit(__('Save'), array('class'=>'btn btn-primary')) ?>
<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
$(function () {
    $('#AlbumParentId').chosen();
});
</script>