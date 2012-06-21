<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php if(count($photos) > 0): ?>
<div class="well menu">
    <?php
    $menu = array(
        'options'=>array('class'=>'nav nav-list'),
        'photos'=>
        array(
            array('title'=> __('Photo Actions'), 'options'=>array('class'=>'nav-header')),
            array('title'=> __('Add Photos'), 'url'=> array('controller'=>'photos', 'action'=>'upload', $album['Album']['id'])),
            array('title'=> __('Slideshow'), 'url'=> array('controller'=>'photos', 'action'=>'slideshow', $photos[0]['Photo']['id'])),
            array('title'=> __('Colorbox'), 'url'=> array('controller'=>'photos', 'action'=> 'colorbox', $album['Album']['id'])),
        )
    );
    print $this->Menu->render($menu);
    ?>
</div>
<?php endif; ?>