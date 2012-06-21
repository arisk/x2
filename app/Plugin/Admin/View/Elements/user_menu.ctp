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
            array('title'=> __('User Actions'), 'options'=>array('class'=>'nav-header')),
            array('title'=> __('Approval Queue'), 'url'=> array('controller'=>'users', 'action'=>'queue')),
        )
    );
    print $this->Menu->render($menu);
    ?>
</div>