<?php
/**
 * AdminAppController Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('AppController', 'Controller');

class AdminAppController extends AppController {
    /* Authorize admins to do it all */
    public function isAuthorized($user){
        if(parent::isAuthorized($user)){
            if ($user['admin'] === true) {
                return true;
            }
        }
        return false;
    }
}