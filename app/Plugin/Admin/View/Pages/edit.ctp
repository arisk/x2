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
$this->Html->addCrumb(__('Content'), array('controller'=>'pages', 'action'=>'index'));
?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<?php
App::uses('L10n', 'Core');
$l10n = new L10n();
$lang = array();
foreach($this->I18n->availableLanguages() as $l){
    $catalog = $l10n->catalog($l);
    $lang[$l10n->map($l)] = $catalog['language'];
}
?>
<?php echo $this->Form->create('Page'); ?>
    <fieldset>
        <legend><?php echo __('Create a new Page'); ?></legend>
        <?php
        echo $this->Form->input('title');
        echo $this->Form->input('html', array('id'=>'tinymce-area', 'rows'=>10));
        echo $this->Form->input('locale', array('type'=>'select', 'options'=>$lang));
        ?>
        <div class="input link">
            <?php if($richtext): ?>
            <?php echo $this->Html->link(__('Disable rich text'), array('action'=>'edit', h($id)), array('class'=>'btn btn-info')); ?>
            <?php else: ?>
            <?php echo $this->Html->link(__('Enable rich text'), array('action'=>'edit', h($id), true), array('class'=>'btn btn-info')); ?>
            <?php endif; ?>
        </div>
        <?php
        echo $this->Form->input('slug');
        ?>
    </fieldset>
<?php echo $this->Form->submit(__('Save'), array('class'=>'btn btn-primary')) ?>
<?php echo $this->Form->end(); ?>
<?php if($richtext): ?>
<?php $this->Html->script('tiny_mce/jquery.tinymce', array('inline' => false)); ?>
<script type="text/javascript">
    $(function() {
        $('#tinymce-area').tinymce({
            // Location of TinyMCE script
            script_url : "<?php echo $this->Html->url('/js/tiny_mce/tiny_mce.js'); ?>",

            // General options
            theme : "advanced",
            plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,

            // Example content CSS (should be your site CSS)
            content_css : "<?php echo $this->Html->url('/css/style.css'); ?>"
        });
    });
</script>
<?php endif; ?>