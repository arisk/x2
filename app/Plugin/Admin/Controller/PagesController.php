<?php
/**
 * Pages Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Page $Page
 */
class PagesController extends AdminAppController{

    /**
     * index method
     *
     * @return void
     */
    public function index(){
        $this->Page->Behaviors->unload('Translate');
        $this->Page->recursive = 0;
        $this->set('pages', $this->paginate());
        $this->set('title_for_layout', __('Home'));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null){
        $this->Page->Behaviors->unload('Translate');
        $this->Page->id = $id;
        if(!$this->Page->exists()){
            throw new NotFoundException(__('Invalid Page'));
        }
        $page = $this->Page->read(null, $id);
        $this->set('page', $page);
        $this->set('title_for_layout', h($page['Page']['title']));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($rich_text = false){
        $this->set('richtext', (bool) $rich_text);
        if($this->request->is('post')){
            if(empty($this->request->data['Page']['slug'])){
                $this->request->data['Page']['slug'] = Inflector::slug($this->request->data['Page']['title'], '-');
            }
            /* Localize */
            if(isset($this->request->data['Page']['locale'])){
                $this->Page->locale = Sanitize::paranoid($this->request->data['Page']['locale']);
            }
            /* Clean the HTML */
            require_once APP.'Vendor'.DS.'HtmlPurifier/HTMLPurifier.standalone.php';
            $purifier = new HTMLPurifier();
            $this->request->data['Page']['html'] = $purifier->purify(nl2br($this->request->data['Page']['html']));
            
            $this->Page->create();
            if($this->Page->save($this->request->data)){
                $this->setFlash(__('The page has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->setFlash(__('The page could not be saved. Please, try again.'), 'bad');
            }
        }
        $this->set('title_for_layout', __('Create New Page'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id, $rich_text = false){
        $this->Page->Behaviors->unload('Translate');
        $this->set('richtext', (bool) $rich_text);
        if(!empty($id)){
            $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        }
        $this->Page->id = $id;
        $page = $this->Page->find('first', array('conditions'=>array('id'=>$id)));
        if(!$page){
            throw new NotFoundException(__('Invalid Page'));
        }
        if($this->request->is('post') || $this->request->is('put')){
            if(empty($this->request->data['Page']['slug'])){
                if(!empty($this->request->data['Page']['title'])){
                    $title = $this->request->data['Page']['title'];
                }
                else{
                    $title = $page['Page']['title'];
                }
                $this->request->data['Page']['slug'] = Inflector::slug($title, '-');
            }
            /* Localize */
            if(isset($this->request->data['Page']['locale'])){
                $this->Page->locale = Sanitize::paranoid($this->request->data['Page']['locale']);
            }
            /* Clean the HTML */
            require_once APP.'Vendor'.DS.'HtmlPurifier/HTMLPurifier.standalone.php';
            $purifier = new HTMLPurifier();
            $this->request->data['Page']['html'] = $purifier->purify(nl2br($this->request->data['Page']['html']));
            
            if($this->Page->save($this->request->data)){
                $this->setFlash(__('The page has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->setFlash(__('The Page could not be saved. Please, try again.'), 'bad');
            }
        }
        else{
            $this->request->data = $page;
        }
        $this->set('id', $id);
        $this->set('title_for_layout', __('Edit').' :: '.h($this->request->data['Page']['title']));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->Page->id = $id;
        if(!$this->Page->exists()){
            throw new NotFoundException(__('Invalid Page'));
        }
        if($this->Page->delete()){
            $this->setFlash(__('Page deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->setFlash(__('Page was not deleted'), 'bad');
        $this->redirect(array('action' => 'index'));
    }
    /**
     * publish method
     *
     * @param string $id
     * @return void
     */
    public function publish($id, $unpublish = false){
        $this->Page->Behaviors->unload('Translate');
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->Page->id = $id;
        if(!$this->Page->exists()){
            throw new NotFoundException(__('Invalid Page'));
        }
        if($unpublish){
            if($this->Page->saveField('published', false)){
                $this->setFlash(__('Content Unpublished'));
            }
            else{
                $this->setFlash(__('Content could not be unpublished. Please try again.'), 'bad');
            }
        }
        else{
            if($this->Page->saveField('published', true)){
                $this->setFlash(__('Content Published'));
            }
            else{
                $this->setFlash(__('Content could not be published. Please try again.'), 'bad');
            }
        }
        $this->redirect(array('action' => 'index'));
    }
    /**
     * promote method
     *
     * @param string $id
     * @return void
     */
    public function promote($id, $unpromote = false){
        $this->Page->Behaviors->unload('Translate');
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->Page->id = $id;
        if(!$this->Page->exists()){
            throw new NotFoundException(__('Invalid Page'));
        }
        if($unpromote){
            if($this->Page->saveField('promoted', false)){
                $this->setFlash(__('Content removed from the front page'));
            }
            else{
                $this->setFlash(__('Content could not be removed from the front page. Please try again.'), 'bad');
            }
        }
        else{
            if($this->Page->saveField('promoted', true)){
                $this->setFlash(__('Content promoted to the front page'));
            }
            else{
                $this->setFlash(__('Content could not be promoted to the front page. Please try again.'), 'bad');
            }
        }
        $this->redirect(array('action' => 'index'));
    }
}