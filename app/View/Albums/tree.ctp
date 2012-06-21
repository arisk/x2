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
$this->Html->script('jquery.ui.min', array('inline'=>false));
$this->Html->script('tree/jquery.dynatree.min', array('inline'=>false));
$this->Html->css('/js/tree/skin/ui.dynatree.css', null, array('inline'=>false));
$this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index'));
$this->Html->addCrumb(__('Album Tree'));
$this->start('left');
echo $this->element('navigation');
$this->end(); ?>
<div class="well well-large">
    <div class="page-header">
        <h1><?php echo __('Album Tree'); ?> <small><?php echo __('Click an Album to Open it'); ?></small></h1>
    </div>
    <div id="tree"></div>
</div>
<p>&nbsp;</p>
<script type="text/javascript">
$(function(){
  // Initialize
  $("#tree").dynatree({
    initAjax: {
      url: "<?php echo $this->Html->url(array('action'=>'children')); ?>"
    },
    onLazyRead: function(node){
        node.appendAjax({
            url: "<?php echo $this->Html->url(array('action'=>'children')); ?>",
            data: {
                key: node.data.key
            }
        });
    },
    onActivate: function(node) {
        if( node.data.href ){
            // Open target
            window.open(node.data.href, node.data.target);
        }
    },
    autoFocus: false
  });
});
</script>