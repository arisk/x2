<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<div class="well menu">
    <?php
    $menu = array(
        'options'=>array('class'=>'nav nav-list'),
        'photos'=>
        array(
            array('title'=> __('Navigation'), 'options'=>array('class'=>'nav-header')),
            array('title'=> __('Home'), 'url'=> '/'),
            array('title'=> __('Albums'), 'url'=> array('controller'=>'albums', 'action'=>'index')),
            array('title'=> __('Album Tree'), 'url'=> array('controller'=>'albums', 'action'=>'tree')),
            array('title'=> __('All Photos'), 'url'=>array('controller'=>'photos', 'action'=>'index')),
            array('title'=> __('Search Photos'), 'url'=> array('controller'=>'photos', 'action'=>'search')),
        )
    );
    print $this->Menu->render($menu);
    ?>
</div>