<?php
/**
 * Dashboard Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Album $Album
 * @property Photo $Photo
 * @property Page $Page
 */
App::uses('Page', 'Admin.Model');
App::uses('Album', 'Admin.Model');
App::uses('Photo', 'Admin.Model');

class DashboardController extends AdminAppController{
    
    public $Album;
    public $Photo;
    public $Page;
    
    /**
     * Dashboard function
     */
    public function index(){
        
        $this->Page = new Page();
        $this->Page->Behaviors->unload('Translate');
        $this->Album = new Album();
        $this->Album->recursive = -1;
        $this->Photo = new Photo();
        $this->Photo->recursive = -1;
        $pages = $this->Page->find('all', array(
            'conditions'=>array('published'=>true),
            'fields' => array('id', 'slug', 'title'),
            'order'=>array('created'=>'DESC'),
            'limit'=>5,
        ));
        $albums = $this->Album->find('all', 
            array(
                'order'=>array('created'=>'DESC'),
                'limit'=>5,
                'fields' => array('id', 'name')
            )
        );
        $photos = $this->Photo->find('all', 
            array(
                'order'=>array('created'=>'DESC'),
                'limit'=>6,
                'fields'=>array('id', 'title', 'file_path', 'file_name')
            )
        );
        $this->set('pages', $pages);
        $this->set('albums', $albums);
        $this->set('photos', $photos);
    }
    public function clear_cache(){
        if(Cache::clear()){
            $this->setFlash(__('Cache cleared successfully'));
        }
        else{
            $this->setFlash(__('Could not clear cache. Please try again or manually clean the cache.'), 'warning');
        }
        $this->redirect($this->referer());
    }
}