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
    echo $this->Form->create('Photo', array(
        'class'=>'navbar-search pull-left',
        'url' => array('action' => 'search')
    ));
    echo $this->Form->input('title', array('class'=>'search-query span2', 
        'placeholder'=>__('Search'), 'div'=>false, 'label'=>false, 'value'=>''));
    echo $this->Form->end();  
?>