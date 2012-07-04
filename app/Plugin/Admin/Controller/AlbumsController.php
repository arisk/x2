<?php
/**
 * Albums Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Album $Album
 */
App::uses('Sanitize', 'Utility');
App::uses('Inflector', 'Utility');

class AlbumsController extends AdminAppController{

    public $components = array('RequestHandler', 'Security');
    public $helpers = array('Cache', 'Form', 'Js' => 'Jquery', 'Time');
    public $cacheAction = array(
        //'index' => 3600,
    );

    public function beforeFilter(){
        parent::beforeFilter();
        if($this->action == 'slug'){
            $this->Security->csrfCheck = false;
        }
    }

    /**
     * index method
     *
     * @return void
     */
    public function index(){
        $this->Album->recursive = -1;
        $this->paginate = array(
            'limit' => 10,
            'contain' => array(
                'Photo'=>array('conditions'=>array('published'=>true), 'fields'=>array('id'), 'limit'=>1),
                'Perm'=>array('fields'=>array('id', 'name')), 
                //'CoverPhoto'=>array('fields'=>array('id', 'file_path', 'file_name', 'album_id'))
            ),
            'fields' => array('id', 'name', 'permission_id', 'created'),
        );
        $this->set('albums', $this->paginate());
        $this->set('title_for_layout', __('Albums'));
    }

    public function slug($slug = null){
        $this->layout = $this->autoRender = false;
        if(isset($this->request->data['Album']['name'])){
            $name = $this->request->data['Album']['name'];
        }
        elseif(!empty($slug)){
            $name = $slug;
        }
        else{
            exit();
        }
        $name = Sanitize::paranoid($name, array(' '));
        print strtolower(Inflector::slug($name, '-'));
        exit();
    }

    /**
     * 
     */
    public function tree($action = null){
        if($action == 'verify'){
            $status = $this->Album->verify();
            if($status === true){
                $this->setFlash(__('The Album tree has successfully passed verification'));
            }
            else{
                $this->set('verify', $status);
            }
        }
        elseif($action == 'recover'){
            if($this->Album->recover()){
                $this->setFlash(__('The Album tree has been restructured'));
            }
            else{
                $this->setFlash(__('There has been an error. You might need to reinstall. Sorry'), 'bad');
            }
        }
        $this->set('title_for_layout', __('Album Tree'));
    }

    public function move(){
        $this->Album->recursive = -1;
        $this->Album->unbindModel(array('belongsTo' => array('Album', 'Photo')));
        $this->autoRender = false;
        $this->autoLayout = false;
        $source_id = Sanitize::paranoid($this->request->data['source']);
        $target = Sanitize::paranoid($this->request->data['target']);
        $mode = Sanitize::paranoid($this->request->data['mode']);

        $parent = $this->Album->getParentNode($target, array('id', 'parent_id'));
        if($parent == false){
            $parent = null;
        }

        $children = $this->Album->children($parent['Album']['id'], true);

        $i = $j = 0;
        /* Find position of target */
        foreach($children as $child){
            if($child['Album']['id'] != $target){
                $i++;
            }
            else{
                break;
            }
        }

        $source = $this->Album->find('first', array(
            'conditions' =>
            array('Album.id' => $source_id),
            'recursive' => false)
        );

        if(!$source){
            throw new NotFoundException(__('Invalid album'));
        }
        /* If source and target are at the same level */
        if($source['Album']['parent_id'] == $parent['Album']['id'] && $mode != 'over'){
            /* Find position of source */
            foreach($children as $child){
                if($source_id != $child['Album']['id']){
                    $j++;
                }
                else{
                    break;
                }
            }
            /* Target is below source. Need to move down */
            if($i > $j){
                $delta = $i - $j;
                if($mode == 'before'){
                    $delta -= 1;
                }
                $this->Album->moveDown($source['Album']['id'], $delta);
            }
            /* Source is below target. Need to move up */
            elseif($j > $i){
                $delta = $j - $i;
                if($mode == 'after'){
                    $delta -= 1;
                }
                $this->Album->moveUp($source['Album']['id'], $delta);
            }
        }
        else{
            $delta = count($children) - $i;
            $this->Album->create();

            $this->Album->id = $source['Album']['id'];
            if($mode == 'over'){
                $data['Album']['parent_id'] = $target;
                $this->Album->save($data, true, array('id', 'parent_id'));
            }
            else{
                if($parent == null){
                    $data['Album']['parent_id'] = null;
                }
                else{
                    $data['Album']['parent_id'] = $parent['Album']['id'];
                }
                $this->Album->save($data, true, array('id', 'parent_id'));
                /* We never check before because the node is always moving up */
                if($mode == 'after'){
                    $delta -= 1;
                }
                $this->Album->moveUp($source['Album']['id'], abs($delta));
            }
        }
        exit(0);
    }
    /**
     * 
     */
    public function children($id = null){
        $this->Album->Behaviors->load('Tree');
        $this->Album->recursive = -1;
        $this->autoRender = false;
        $this->autoLayout = false;

        if($id === null || is_numeric($id)){

            $output = array();

            if(isset($this->request->query['key']) && is_numeric($this->request->query['key'])){
                $id = $this->request->query['key'];
            }
            foreach($this->Album->children($id, true, array('id', 'name')) as $child){
                if($this->Album->childCount($child['Album']['id'], true)){
                    $output[] = array(
                        'title' => $child['Album']['name'],
                        'isFolder' => true,
                        'key' => $child['Album']['id'],
                        'isLazy' => true,
                    );
                }
                else{
                    $output[] = array(
                        'title' => $child['Album']['name'],
                        'key' => $child['Album']['id'],
                        'href' => Router::url(array('controller'=>'albums', 'action'=>'view', $child['Album']['id']), true),
                    );
                }
            }
            $this->response->type('json');
            $this->response->body(json_encode($output));
            $this->response->send();
            exit();
        }
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id, $slug = null){
        $this->Album->Photo->recursive = -1;
        $this->paginate = array('Photo' =>
            array(
                'limit' => 10,
                'fields' => array('id', 'name', 'title', 'file_path', 'file_name', 'created', 'published', 'views'),
            )
        );
        $album = $this->Album->find('first', 
            array(
                'contain' => array(
                    'CoverPhoto'=>array('fields'=>array('id', 'title', 'file_name', 'file_path')), 
                    'ChildAlbum'=>array('fields'=>array('id', 'name')),
                ),
                'fields'=> array('id', 'name', 'description', 'created'), 
                'conditions' => array(
                    'Album.id' => (int) $id,
                )
            )
        );
        if(!$album){
            throw new NotFoundException(__('Invalid Album'));
        }
        $this->set('album', $album);
        $photos = $this->paginate('Photo', array('album_id' => $album['Album']['id']));
        $this->set('photos', $photos);
        $this->set('title_for_layout', __('Album').' :: '.h($album['Album']['name']));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add(){
        if($this->request->is('post')){
            $this->Album->create();
            if(empty($this->request->data['Album']['slug'])){
                if(!empty($this->request->data['Album']['name'])){
                    $this->request->data['Album']['slug'] =
                        strtolower(Inflector::slug($this->request->data['Album']['name'], '-'));
                }
            }
            if($this->Album->save($this->request->data)){
                $this->setFlash(__('The album has been saved. Add some pictures'));
                $this->redirect(array('controller'=>'photos', 'action'=>'upload', $this->Album->id));
            }
            else{
                $this->setFlash(__('The album could not be saved. Please, try again.'), 'bad');
            }
        }
        $parents = $this->Album->generateTreeList();
        $permissions = $this->Album->Perm->find('list', array('id', 'name'));
        $this->set(compact('parents', 'permissions'));
        $this->set('title_for_layout', __('Create New Album'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Album->id = $id;
        if(!$this->Album->exists()){
            throw new NotFoundException(__('Invalid album'));
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Album->save($this->request->data)){
                $this->setFlash(__('The album has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->setFlash(__('The album could not be saved. Please, try again.'), 'bad');
            }
        }
        else{
            $this->request->data = $this->Album->read(null, $id);
        }
        $parents = $this->Album->generateTreeList(array('id !=' => $id));
        $permissions = $this->Album->Perm->find('list', array('id', 'name'));
        $this->set(compact('parents', 'permissions'));
        $this->set('title_for_layout', __('Album').' :: '.h($this->request->data['Album']['name']));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post') && !$this->request->is('delete')){
            throw new MethodNotAllowedException();
        }
        $this->Album->id = $id;
        if(!$this->Album->exists()){
            throw new NotFoundException(__('Invalid album'));
        }
        $this->Album->Photo->recursive = -1;
        $this->Album->Photo->Behaviors->attach('Admin.Image');
        $photos = $this->Album->Photo->find('all', 
            array(
                'conditions'=>array('album_id'=>$id),
                'fields'=>array('id')
            )
        );
        if($photos){
            foreach($photos as $photo){
                if(!$this->Album->Photo->remove($photo['Photo']['id'])){
                    $this->setFlash(__('Could not delete photo with id: %d', h($photo['Photo']['id'])), 'bad');
                    $this->redirect(array('action'=>'index'));
                }
            }
        }
        if($this->Album->removeFromTree($id, true)){
            $this->setFlash(__('Album deleted'));
        }
        else{
            $this->setFlash(__('Album was not deleted'), 'bad');
        }
        $this->redirect(array('action' => 'index'));
    }
}