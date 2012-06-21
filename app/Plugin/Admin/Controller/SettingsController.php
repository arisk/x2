<?php
/**
 * Settings Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Setting $Setting
 */
App::uses('Folder', 'Utility');

class SettingsController extends AdminAppController{

    public $helpers = array('I18n.I18n');
    /**
     * index method
     *
     * @return void
     */
    public function index(){
        $folder = new Folder(APP.DS.'View'.DS.'Themed');
        $contents = $folder->read(true, true);
        $th = $contents[0];
        $themes = array();
        foreach($th as $t){
            $themes[$t] = $t;
        }
        if($this->request->is('post')){
            $saved = $this->Setting->saveAll($this->request->data['Setting'], array('validate'=>false));
            if($saved){
                $this->setFlash(__('Your settings have been saved'));
                $this->redirect(array('action'=>'index'));
            }
            else{
                $this->setFlash(__('Could not save settings. Please try again.'), 'bad');
            }
        }
        $this->Setting->recursive = 0;
        $settings = $this->Setting->find('all');
        $this->request->data = $settings;
        foreach($settings as $key => $val){
            if($val['Setting']['name'] == 'Theme'){
                $one = unserialize($val['Setting']['extra']);
                $two = array('options'=>$themes);
                $arr = array_merge($one, $two);
                $val['Setting']['extra'] = $arr;
                $settings[$key] = $val;
            }
        }
        $settings = Set::combine($settings, '{n}.Setting.id', '{n}.Setting', '{n}.Setting.section');
        $this->set('settings', $settings);
        $this->set('title_for_layout', __('Settings'));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view(){
        $this->paginate = array('limit'=>15);
        $this->set('settings', $this->paginate());
        $this->set('title_for_layout', __('Application Settings'));
    }
}