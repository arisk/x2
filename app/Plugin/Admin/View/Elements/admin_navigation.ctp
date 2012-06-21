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
            array('title'=> __('Dashboard'), 'url'=> array('controller'=>'dashboard', 'action'=>'index')),
            array('title'=> __('Manage Content'), 'url'=> array('controller'=>'pages', 'action'=>'index')),
            array('title'=> __('Manage Albums'), 'url'=> array('controller'=>'albums', 'action'=>'index')),
            array('title'=> __('Upload Photos'), 'url'=> array('controller'=>'photos', 'action'=>'upload')),
            array('title'=> __('Manage Photos'), 'url'=> array('controller'=>'photos', 'action'=>'index')),
            array('title'=> __('Find Duplicates'), 'url'=> array('controller'=>'photos', 'action'=>'duplicates')),
            array('title'=> __('Search Photos'), 'url'=> array('controller'=>'photos', 'action'=>'search')),
            array('title'=> __('Manage Users'), 'url'=> array('controller'=>'users', 'action'=>'index')),
            array('title'=> __('Settings'), 'url'=> array('controller'=>'settings', 'action'=>'index')),
        )
    );
    print $this->Menu->render($menu);
    ?>
</div>