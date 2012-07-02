<?php
/**
 * X2 Helper 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('AppHelper', 'View/Helper');

/**
 *@property HtmlHelper $Html 
 */
class X2Helper extends AppHelper{
    public function photoUrl($for_image = true){
        $base = Configure::read('X2.Photo.Base_URL');
        if(!empty($base)){
            return h($base).'/'.h(Configure::read('X2.Dir.P')).'/';
        }
        elseif($for_image === false){
            return Router::url('/').h(Configure::read('X2.Dir.P')).'/';
        }
        return '/'.h(Configure::read('X2.Dir.P')).'/';
    }
}