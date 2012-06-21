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
$this->Html->script('tree/jquery.dynatree.min', array('inline'=>false));
$this->Html->css('/js/tree/skin/ui.dynatree.css', null, array('inline'=>false));
$this->extend('/Layouts/two');
$this->Html->addCrumb(__('Albums'), array('controller'=>'albums', 'action'=>'index'));
$this->Html->addCrumb(__('Album Tree'));
$this->start('left');
    echo $this->element('admin_navigation');
    echo $this->element('album_menu');
$this->end(); ?>
<div class="well well-large">
    <div class="page-header">
        <h1><?php echo __('Album Tree'); ?> <small><?php echo __('Drag and Drop to rearrange albums'); ?></small></h1>
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
    autoFocus: false,
    dnd: {
      onDragStart: function(node) {
        return true;
      },
      onDragStop: function(node) {
      },
      autoExpandMS: 1000,
      preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
      onDragEnter: function(node, sourceNode) {
        return true; 
      },
      onDragOver: function(node, sourceNode, hitMode) {
        if(node.isDescendantOf(sourceNode)){
          return false;
        }
      },
      onDrop: function(node, sourceNode, hitMode, ui, draggable) {
        /** This function MUST be defined to enable dropping of photos on
         * the tree.
         */
        //logMsg("tree.onDrop(%o, %o, %s)", node, sourceNode, hitMode);
        $.post("<?php echo $this->Html->url(array('action'=>'move')); ?>", 
        { source: sourceNode.data.key, target: node.data.key, mode: hitMode}, function(data){
            //alert('success');
            sourceNode.move(node, hitMode);
        }).error(function() { alert("<?php echo __('There was an error moving this album'); ?>"); });
        
        // expand the drop target
        //sourceNode.expand(true);
      },
      onDragLeave: function(node, sourceNode) {
      }
    }
  });
});
</script>