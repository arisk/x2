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
App::uses('L10n', 'Core');
$l10n = new L10n();
$lang = array();
foreach($this->I18n->availableLanguages() as $l){
    $catalog = $l10n->catalog($l);
    $lang[$l] = $catalog['language']; 
}
?>
<?php $this->extend('/Layouts/two'); ?>
<?php $this->Html->addCrumb(__('Settings')); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php echo $this->element('settings_menu'); ?>
<?php $this->end(); ?>
<h2><?php echo __('Settings'); ?></h2>
<ul class="nav nav-tabs" id="tabs">
    <?php foreach($settings as $key => $val): ?>
    <?php $key = h($key); ?>
    <li><a href="#<?php echo strtolower($key); ?>" data-toggle="tab"><?php echo $key ?></a></li>
    <?php endforeach; ?>
</ul>
<?php echo $this->Form->create('Setting'); ?>
<div class="tab-content">
    <?php foreach($settings as $key => $section): ?>
    <?php $key = h($key); ?>
    <div class="tab-pane" id="<?php echo strtolower($key); ?>">
        <?php foreach($section as $setting): ?>
            <?php if($setting['name'] == 'Language'): ?>
                <?php $setting['extra'] = array('options'=>$lang); ?>
            <?php endif; ?>
            <?php $name = Inflector::humanize(h($setting['name'])); ?>
            <?php if($setting['type'] == 'checkbox'): ?>
                <?php $s = array('label'=>$name, 'type'=>h($setting['type']), 'checked'=>(bool)h($setting['value'])); ?>
            <?php elseif($setting['type'] == 'textarea'): ?>
                <?php $s = array('label'=>$name, 'type'=>h($setting['type']), 'value'=>$setting['value']); ?>
            <?php else: ?>
                <?php if($setting['type'] == 'int'): ?>
                    <?php $setting['type']= 'text'; ?>
                <?php endif; ?>
                <?php $s = array('label'=>$name, 'type'=>h($setting['type']), 'value'=>h($setting['value'])); ?>
            <?php endif; ?>
            <?php if(!empty($setting['extra'])): ?>
                <?php $s = array_merge($setting['extra'], $s); ?>
            <?php endif; ?>
            <?php $setting_id = h($setting['id']); ?>
            <?php echo $this->Form->input('Setting.'.$setting_id.'.id', array('type'=>'hidden', 'value'=>$setting_id)); ?>
            <?php echo $this->Form->input('Setting.'.$setting_id.'.value', $s); ?>
            <span><?php echo h($setting['description']); ?></span>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
<br />
<?php echo $this->Form->submit(__('Save'), array('class'=>'btn btn-primary')) ?>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
  $(function () {
    $('#tabs a:first').tab('show');
  })
</script>