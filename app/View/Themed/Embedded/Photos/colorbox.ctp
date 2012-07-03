<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $url = $this->X2->photoUrl(); ?>
<?php $show_date = Configure::read('X2.Photo.Show_Photo_Date'); ?>
<div class="span8">
<?php $photo_links = ''; ?>
<?php $count = count($photos); ?>
<?php for($i = 0; $i < $count; $i++): ?>
        <?php if($i % 4 == 0): ?>
        <ul class="row photos thumbnails">
            <?php endif; ?>
        <li class="span2">
            <?php
            $title = !empty($photos[$i]['Photo']['title']) ? $photos[$i]['Photo']['title'] : $photos[$i]['Photo']['name'];
            $image_s_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                    Configure::read('X2.Dir.S').'/'.$photos[$i]['Photo']['file_name']);
            $image_l_url = h($url.$photos[$i]['Photo']['file_path'].'/'.
                    Configure::read('X2.Dir.L').'/'.$photos[$i]['Photo']['file_name']);
            print $this->Html->link(
                $this->Html->image($image_s_url, 
                    array('alt' => h($title), 'class'=>'thumbnail')
                ), 
                $image_l_url, 
                array('class' => 'gallery', 
                    'escape' => false, 'title'=>h($title),
                    'id'=>'img_'.h($photos[$i]['Photo']['id']), 
                )
            );
            $photo_links.= $this->Html->link(null, $image_l_url, 
                    array('id'=>'cimg_'.h($photos[$i]['Photo']['id']), 'class' => 'gallery', 'title'=>h($title)));
            ?>
            <div class="caption">
                <?php
                $title = !empty($photos[$i]['Photo']['title']) ? $photos[$i]['Photo']['title'] : $photos[$i]['Photo']['name']; 
                ?>
                <?php echo $this->Html->link($title, 
                        array('controller'=>'photos', 'action'=>'view', h($photos[$i]['Photo']['id']))); ?>
                <?php if($show_date): ?>
                <p><small><?php echo $this->Time->nice(h($photos[$i]['Photo']['created'])); ?></small></p>
                <?php endif; ?>
            </div>
        </li>
        <?php if($i % 4 == 3): ?>
        </ul>
    <?php endif; ?>
<?php endfor; ?>
<?php if(($i-1) % 4 != 3): ?>
    <?php echo '</ul>'; ?>
<?php endif; ?>
<div id="photo_links" style="display: none;"><?php echo $photo_links; ?></div>
</div>
<script type="text/javascript"> 
$(function(){
    $('.gallery').click(function(e){
        e.preventDefault(); 
        parent.showColorbox($('#photo_links'), $(this).attr('id'));
        return false;
    });
});
</script>