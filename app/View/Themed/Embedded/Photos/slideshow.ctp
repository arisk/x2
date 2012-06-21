<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<div class="span8">
    <div id="slideshow" class="carousel slide">
        <div class="carousel-inner">
            <?php foreach($photos as $photo): ?>
                <?php $class = 'item'; ?>
                <?php if($photo['Photo']['id'] == $id): ?>
                    <?php $class .= ' active'; ?>
                <?php endif; ?>
                <div class="<?php echo $class; ?>">
                    <?php 
                    $url = '/'.Configure::read('X2.Dir.P').'/'.$photo['Photo']['file_path'].'/'.
                            Configure::read('X2.Dir.L').'/'.$photo['Photo']['file_name'];
                    $title = isset($photo['Photo']['title']) ? $photo['Photo']['title'] : $photo['Photo']['name'];
                    ?>
                    <h4><?php print $title; ?></h4>
                    <?php
                    echo $this->Html->Image($url, array('alt'=>$title)); 
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="left carousel-control" href="#slideshow" data-slide="prev">‹</a>
        <a class="right carousel-control" href="#slideshow" data-slide="next">›</a>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#slideshow').carousel({interval: 5000});
    });
</script>