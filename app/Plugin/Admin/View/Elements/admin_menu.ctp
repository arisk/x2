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
$menu = array(
    'options'=>array('class'=>'nav'),
    'photos'=>
    array(
        array('title'=> __('Dashboard'), 'url'=> array('controller'=>'dashboard', 'action'=>'index')),
        array('title'=> __('Albums'), 'url'=> array('controller'=>'albums', 'action'=>'index')),
        array('title'=> __('Photos'), 'url'=> array('controller'=>'photos', 'action'=>'index')),
    ));
print $this->Menu->render($menu);
?>