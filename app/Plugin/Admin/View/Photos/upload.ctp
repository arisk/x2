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
$this->extend('/Layouts/one');
$this->Html->css('jquery.fileupload-ui.min', null, array('inline' => false));
$this->Html->script('tmpl.min', array('inline' => false));
$this->Html->script('locale.min', array('inline' => false));
$this->Html->script('jquery.fileupload.min', array('inline' => false));
$this->Html->script('jquery.fileupload-ui.min', array('inline' => false));
$this->Html->script('jquery.iframe-transport.min', array('inline' => false));
$this->Html->script('chosen.jquery.min', array('inline'=>false));
$this->Html->css('chosen.min', null, array('inline'=>false));
?>
<?php $this->Html->addCrumb(__('Upload')); ?>
<?php echo $this->Form->create('Photo', array('id'=>'uploader', 'enctype' => 'multipart/form-data')); ?>
<div class="well well-large">
    <fieldset>
        <legend><?php echo __('Upload'); ?></legend>
        <?php
        echo $this->Form->input('album_id', array('data-placeholder'=>__('Select an Album'), 'empty'=>''));
        ?>
    </fieldset>
    <br />
    <div class="row fileupload-buttonbar">
        <div class="span6">
            <span class="btn btn-success fileinput-button">
                <i class="icon-plus icon-white"></i>
                <span><?php echo __('Add Files'); ?></span>
                <?php
                echo $this->Form->input('file', array(
                    'type' => 'file',
                    'label' => false,
                    'div' => false,
                    'class' => 'fileUpload',
                    'multiple' => 'multiple'
                ));                
                ?>
            </span>
            <button type="submit" class="btn btn-primary start">
                <i class="icon-upload icon-white"></i>
                <span><?php echo __('Start upload') ?></span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="icon-ban-circle icon-white"></i>
                <span><?php echo __('Cancel upload'); ?></span>
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="icon-trash icon-white"></i>
                <span><?php echo __('Delete'); ?></span>
            </button>
            <input type="checkbox" class="toggle">
        </div>
        <div class="span5 fileupload-progress fade">
            <div class="progress progress-success progress-striped active">
                <div class="bar" style="width:0%;"></div>
            </div>
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <div class="fileupload-loading"></div>
    <br />
    <table class="table table-striped"><tbody class="files"></tbody></table>
</div>
<!-- upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!--  download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" target="_blank"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" target="_blank">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <?php echo $this->Form->checkbox('delete', array('id'=>false, 'hiddenField'=>false));?>
        </td>
    </tr>
{% } %}
</script>
<script type="text/javascript">
    $(function(){
        $('#PhotoAlbumId').chosen();
        $('#uploader').fileupload();
    });
</script>
<?php echo $this->Form->end(); ?>