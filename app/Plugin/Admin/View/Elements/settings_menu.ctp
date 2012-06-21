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
            array('title'=> __('Settings'), 'options'=>array('class'=>'nav-header')),
            array('title'=> __('View All Settings'), 'url'=> array('controller'=>'settings', 'action'=>'view')),
            array('title'=> __('Clear Application Cache'), 'url'=> array('controller'=>'dashboard', 'action'=>'clear_cache')),
        )
    );
    print $this->Menu->render($menu);
    ?>
</div>