<?php
/**
 * Metadata Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Metadata $Metadata
 */
class MetadataController extends AdminAppController{
    
    public function delete($photo_id = null){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $md = $this->Metadata->find('first', array('conditions'=>array('photo_id'=>$id), 'fields'=>array('id')));
        if(!$md){
            throw new NotFoundException(__('Invalid Photo ID'));
        }
        $this->Metadata->id = $md['Metadata']['id'];
        if($this->Metadata->delete()){
            $this->setFlash(__('EXIF Metadata deleted'));
        }
        else{
            $this->setFlash(__('EXIF Metadata were not deleted'), 'bad');
        }
        $this->redirect(array('controller'=>'photos', 'action' => 'view', $id));
    }
}