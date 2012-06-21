<?php
/**
 * Custom exception renderer. This helps if there is no connection present 
 * because we load the config file from the DB in the bootstrap file.
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {
    protected function _getController($exception) {
        CakePlugin::load('I18n');
        return parent::_getController($exception);
    }
    public function missingConnection($error){
        $message = $error->getAttributes();
        print '<h1>'.__('Error Connecting to the Database. Please check your connection settings').'<h1>';
        print '<h2>'.$message['class'].'</h2>';
    }
}