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
<?php $this->Html->addCrumb(h($album['Album']['name']), 
        array('controller'=>'albums', 'action'=>'view', h($album['Album']['id']))); ?>
<?php $this->Html->addCrumb(__('Slideshow'));?>
<?php $url = $this->X2->photoUrl(); ?>
<div class="row">
    <div class="span10 offset1">
        <div id="slideshow" class="carousel slide">
            <div class="carousel-inner">
                <?php foreach($photos as $photo): ?>
                    <?php $class = 'item'; ?>
                    <?php if($photo['Photo']['id'] == $id): ?>
                        <?php $class .= ' active'; ?>
                    <?php endif; ?>
                    <div class="<?php echo $class; ?>">
                        <?php 
                        $imgeurl = $url.$photo['Photo']['file_path'].'/'.
                                Configure::read('X2.Dir.L').'/'.$photo['Photo']['file_name'];
                        $title = isset($photo['Photo']['title']) ? h($photo['Photo']['title']) : h($photo['Photo']['name']);
                        ?>
                            <h4><?php print $title; ?></h4>
                        <?php
                        echo $this->Html->image($imgeurl, array('alt'=>$title)); 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="left carousel-control" href="#slideshow" data-slide="prev">‹</a>
            <a class="right carousel-control" href="#slideshow" data-slide="next">›</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#slideshow').carousel({interval: 5000});
    });
</script>