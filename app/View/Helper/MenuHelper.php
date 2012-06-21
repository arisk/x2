<?php
/**
 * Simple Menu Helper for CakePHP
 *  
 * Conventient to use when switching css classes to the currently active url.
 * I wrote this class to be used with the bootstrap library.
 *
 * Usage:
 * In your controller include the helper
 * class AppController extends Controller{
 *     public $helpers = array('Menu');
 * }
 *  
 * Anywhere in your view create a menu array
 * 
 * $menu = array(
 *    'options'=>array('class'=>'nav'),
 *    'photos'=>
 *    array(
 *        array('title'=>'Home', 'url'=> array('controller'=>'index', 'action'=>'home')),
 *        array('title'=>'Users', 'url'=> array('controller'=>'users', 'action'=>'index'), 'photos'=>
 *            array(
 *                array('title'=> 'Login', 'url'=>array('controller'=>'users', 'action'=>'login')),
 *            )
 *        ),
 *    )
 * );
 * 
 * Then render it
 * print $this->Menu->render($menu);
 * 
 * @author  Aris Karageorgos <aris@deepspacehosting.com>
 * @version 1.0
 * @link    http://deepspacehosting.com
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * 
 */
App::uses('AppHelper', 'View/Helper');
/**
 *@property HtmlHelper $Html 
 */
class MenuHelper extends AppHelper{

    public $helpers = array('Html');
    public $menu = array();
    
    /**
     * Adds an photo to the menu array.
     * @param array $photo Photo to be added to the menu
     */
    public function addPhoto($photo){
        $this->menu['photos'][] = $photo; 
    }
    
    /**
     * Adds photos to the $menu array
     * @param array $photos Photos to be added to the menu
     * @return void
     */
    public function addPhotos($photos){
        foreach($photos as $photo){
            $this->addPhoto($photo);
        }
    }
    
    /**
     * This methods recursively generates the menu
     * @param type $menu array of nested photos
     * @param boolean $merge whether to merge this input with $this->menu
     * @return string nested list elements 
     */
    public function render($menu = array(), $merge = true){
        if($merge){
            $menu = array_merge($menu, $this->menu);
        }
        return $this->Html->tag('ul', $this->buildPhotos($menu['photos']), $menu['options']);
    }
    /**
     * Render list photos within each recursive list
     * @param array $photos list photos to be placed inside the list
     * @return string list photos
     */
    public function buildPhotos($photos){
        $list_photos = $li = '';
        foreach($photos as $photo){
            /* Default Access is true */
            if(!isset($photo['access'])){
                $photo['access'] = true;
            }
            if($photo['access'] === false){
                continue;
            }
            if(!isset($photo['options'])){
                $photo['options'] = array();
            }
            /* This is needed for nav headers */
            $url = isset($photo['url']) ? $this->url($photo['url']) : null;
            
            /* Check for if we should add the active class */
            $active = $this->request->here == $url? 'active': null;
            if(isset($photo['options']['class']) && !empty($active)){
                $photo['options']['class'] .= " {$active}";
            }
            elseif(!empty($active)){
                $photo['options']['class'] = $active;
            }
            $options = $photo['options'];
            
            if(isset($photo['photos']) && is_array($photo['photos'])){
                /* Recursive call within the current photo */
                if($photo['access'] === true){
                    if(isset($photo['url'])){
                        $li = $this->Html->tag('li', $this->Html->link($photo['title'], 
                            $photo['url']).$this->render($photo), $options);
                    }
                    else{
                        $li = $this->Html->tag('li', $photo['title'].$this->render($photo), $options);                        
                    }
                }
            }
            elseif($photo['access'] === true){
                if(isset($photo['url'])){
                    $li = $this->Html->tag('li', $this->Html->link($photo['title'], 
                            $photo['url']), $options);
                }
                else{
                    $li = $this->Html->tag('li', $photo['title'], $options);                    
                }
            }

            $list_photos .= $li;            
        }
        return $list_photos;
    }
}