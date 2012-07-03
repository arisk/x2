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
        array('title'=> __('Home'), 'url'=> '/'),
        array('title'=> __('Albums'), 'url'=> array('plugin'=>null, 'controller'=>'albums', 'action'=>'index')),
        array('title'=> __('Photos'), 'url'=> array('plugin'=>null, 'controller'=>'photos', 'action'=>'index')),
        array('title'=> __('Login'), 'url'=> array('plugin'=>null, 'controller'=>'users', 'action'=>'login'), 
            'access'=> !$this->Session->check('Auth.User.id')),
    ));
print $this->Menu->render($menu);
?>