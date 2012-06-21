<?php
/**
 * Behavior that allows uploading, resizing, croping and other functions 
 * related to image management.
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
require_once APP.'Vendor'.DS.'WideImage/WideImage.php';
require_once APP.'Vendor'.DS.'Geocoder/Geocoder.php';

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ImageBehavior extends ModelBehavior{

    // Sizes
    public $sx, $sy, $xsx, $xsy, $lx, $ly;
    // Directories
    public $xs, $s, $l, $o, $p;
    public $file;
    public $lwidth = 0;
    public $lheight = 0;
    protected $_defaults = array();
    
    /**
     * Get image sizes and directories 
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param array $config Possible default configurations. Currently not used
     * 
     * @return void
     */
    public function setup(Model $model, $config = array()){
        $this->xsx = Configure::read('X2.Size.XSX');
        $this->xsy = Configure::read('X2.Size.XSY');
        $this->sx = Configure::read('X2.Size.SX');
        $this->sy = Configure::read('X2.Size.SY');
        $this->lx = Configure::read('X2.Size.LX');
        $this->ly = Configure::read('X2.Size.LY');
        $this->xs = Configure::read('X2.Dir.XS');
        $this->s = Configure::read('X2.Dir.S');
        $this->l = Configure::read('X2.Dir.L');
        $this->o = Configure::read('X2.Dir.O');
        $this->p = Configure::read('X2.Dir.P');
    }
    /**
     * Uploads an individual Photo. 
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param array $d Data passed in from the controller
     * 
     * @return StdClass Error or uploaded Photo details
     */
    public function upload(Model $model, $d){
        $file = new stdClass();
        if(!empty($d['Photo']['file'])){
            $file->name = isset($d['Photo']['file']['name']) ? $d['Photo']['file']['name'] : null;
            $file->size = (int) $d['Photo']['file']['size'];
            /* If there is an error process it and return */
            if($d['Photo']['file']['error'] !== UPLOAD_ERR_OK){
                return $this->_processError($d['Photo']['file']['error']);
            }
            if(is_uploaded_file($d['Photo']['file']['tmp_name'])){
                $exif = array();
                if(function_exists('exif_read_data') && $d['Photo']['file']['type'] == 'image/jpeg' ||
                        $d['Photo']['file']['type'] == 'image/jpg'){
                    $exif = exif_read_data($d['Photo']['file']['tmp_name'], null, true);
                    $this->_processExif($exif, $d);
                    $d['Metadata']['data'] = serialize($exif);
                }
                try{
                    $image = WideImage::loadFromFile($d['Photo']['file']['tmp_name']);
                }
                catch(Exception $e){
                    $file->error = __('Unable to load the photo');
                    return $file;
                }
                $d['Photo']['width'] = $image->getWidth();
                $d['Photo']['height'] = $image->getHeight();
                $d['Photo']['size'] = filesize($d['Photo']['file']['tmp_name']);
                $d['Photo']['name'] = $d['Photo']['file']['name'];
                $d['Photo']['slug'] = strtolower(Inflector::slug($d['Photo']['file']['name'], '-'));

                $title = pathinfo($d['Photo']['file']['name'], PATHINFO_FILENAME);
                $d['Photo']['slug'] = strtolower(Inflector::slug($title, '-'));
                $d['Photo']['title'] = Inflector::humanize($title);

                /* Mimetype detection if possible */
                if(function_exists('finfo_open')){
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $d['Photo']['type'] = finfo_file($finfo, $d['Photo']['file']['tmp_name']);
                }
                else{
                    $d['Photo']['type'] = $d['Photo']['file']['type'];
                }
                $d['Photo']['hash'] = hash_file('sha512', $d['Photo']['file']['tmp_name']);

                $extension = pathinfo($d['Photo']['file']['name'], PATHINFO_EXTENSION);
                $file_name = hash('md5', $d['Photo']['hash']);

                if(isset($exif['EXIF']['DateTimeOriginal'])){
                    $timestamp = strtotime($exif['EXIF']['DateTimeOriginal']);
                    $d['Photo']['taken'] = date('Y-m-d H:i:s', $timestamp);
                }
                else{
                    $d['Photo']['taken'] = date('Y-m-d H:i:s');
                }

                $path = $this->_getPath($exif);
                /* Create the directories required if they don't already exist */
                $folder = $this->_createDirs($path);
                if($folder === false){
                    $file->error = __('Cannot write to the directory. Check your permissions');
                    return $file;
                }
                if(file_exists(WWW_ROOT.$this->p.DS.$path.DS.$this->o.DS.$file_name.'.'.$extension)){
                    $i = 1;
                    $info = pathinfo(WWW_ROOT.$this->p.DS.$path.DS.$this->o.DS.$file_name.'.'.$extension);
                    while(true){
                        if(file_exists($info['dirname'].DS.$info['filename'].'_'.$i.'.'.$info['extension'])){
                            $i++;
                        }
                        else{
                            $file_name = $info['filename'].'_'.$i;
                            break;
                        }
                    }
                }
                $d['Photo']['file_name'] = $file_name.'.'.$extension;
                $d['Photo']['file_path'] = $path;
                /* Write the different images sizes to disk */
                $this->_processFiles($image, $path, $file_name, $extension);
                $d['Photo']['lwidth'] = $this->lwidth;
                $d['Photo']['lheight'] = $this->lheight;
                $image->destroy();

                unset($d['Photo']['file']);
                /* Get the Location */
                if(isset($exif['GPS']) && !empty($exif['GPS'])){
                    $location = $this->_processGPS($exif['GPS']);
                    if(!empty($location)){
                        $d['Photo']['location'] = $location;
                    }
                }
                /* Save the new photo + metadata */
                if(!$model->saveAssociated($d)){
                    $file->error = __('Error Saving Record');
                    return $file;
                }
                /* Prepare the return URL to be displayed on the AJAX upload form */
                $file->url = Router::url(array('controller'=>'photos', 'action'=>'view', $model->id));
                $file->thumbnail_url = Router::url(DS.$this->p.DS.$path.DS.$this->s.DS.$file_name.'.'.$extension, true);
                $file->delete_url = Router::url(array('controller' => 'photos', 'action' => 'delete', $model->id), true);
                $file->delete_type = 'DELETE';
            }
            else{
                $file->error = __('Not a File');
            }
        }
        else{
            $file->error = __('Not a File');
        }
        return $file;
    }
    /**
     * Process EXIF Data 
     * 
     * @param array $exif EXIF Data to be manipulated
     * @param array $d Data passed in from the controller
     * 
     * @return boolean true
     */
    public function _processExif(&$exif, $d){
        /* Correct the filename */
        if(isset($exif['FILE']['FileName'])){
            $exif['FILE']['FileName'] = $d['Photo']['file']['name'];
        }
        return true;
    }
    /**
     * Process error codes 
     * 
     * @param integer $error PHP Error code
     * 
     * @return StdClass Error details
     */
    public function _processError($error){
        $file = new stdClass();
        switch($error){
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $file->error = __('The image exceeds the maximum file size in your configuration');
                break;
            case UPLOAD_ERR_PARTIAL:
                $file->error = __('The File was only partially uploaded. Please try again');
                break;
            case UPLOAD_ERR_NO_FILE:
                $file->error = __('No file was uploaded');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $file->error = __('There is no temporary folder to place the file on your system');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $file->error = __('Can\'t write the file to disk');
                break;
            case UPLOAD_ERR_EXTENSION:
                $file->error = __('An unknown extension caused the upload to fail');
                break;
            default:
                break;
        }
        return $file;
    }
    /**
     * Get path for current image. Will look for data from EXIF. If none is found 
     * the current date will be used. 
     * 
     * @param array $exif EXIF Data
     * 
     * @return string path
     */
    public function _getPath($exif){
        if(isset($exif['EXIF']['DateTimeOriginal'])){
            $timestamp = strtotime($exif['EXIF']['DateTimeOriginal']);
            $year = date('Y', $timestamp);
            $month = date('F', $timestamp);
            $day = date('d', $timestamp);
        }
        else{
            $year = date('Y');
            $month = date('F');
            $day = date('d');
        }
        $path = $year.DS.$month.DS.$day;
        return $path;
    }
    /**
     * Create directory structure for a Photo to be stored
     * 
     * @param $path Model EXIF Data
     * 
     * @return Folder|boolean Folder or boolean 
     */
    protected function _createDirs($path){
        $folder = new Folder(WWW_ROOT.$this->p.DS.$path, true);
        if(!is_writable(WWW_ROOT.$this->p.DS.$path)){
            return false;
        }
        $folder->create(WWW_ROOT.$this->p.DS.$path.DS.$this->xs, 0755);
        $folder->create(WWW_ROOT.$this->p.DS.$path.DS.$this->s, 0755);
        $folder->create(WWW_ROOT.$this->p.DS.$path.DS.$this->l, 0755);
        $folder->create(WWW_ROOT.$this->p.DS.$path.DS.$this->o, 0755);
        return $folder;
    }
    /**
     * Removes empty directories after deleting an image 
     * 
     * @param string $path Path to be deleted
     * 
     * @return void
     */
    protected function _removeEmptyDirs($path){
        $folder = new Folder(WWW_ROOT.$this->p.DS.$path);
        if(!is_writable(WWW_ROOT.$this->p.DS.$path)){
            return false;
        }
        if($folder->cd(WWW_ROOT.$this->p.DS.$path.DS.$this->xs)){
            $contents = $folder->read(true, true);
            $folders = $contents[0];
            $files = $contents[1];
            if(empty($folders) && empty($files)){
               $folder->delete();
            }
        }
        if($folder->cd(WWW_ROOT.$this->p.DS.$path.DS.$this->s)){
            $contents = $folder->read(true, true);
            $folders = $contents[0];
            $files = $contents[1];
            if(empty($folders) && empty($files)){
               $folder->delete();
            }
        }
        if($folder->cd(WWW_ROOT.$this->p.DS.$path.DS.$this->l)){
            $contents = $folder->read(true, true);
            $folders = $contents[0];
            $files = $contents[1];
            if(empty($folders) && empty($files)){
               $folder->delete();
            }
        }
        if($folder->cd(WWW_ROOT.$this->p.DS.$path.DS.$this->o)){
            $contents = $folder->read(true, true);
            $folders = $contents[0];
            $files = $contents[1];
            if(empty($folders) && empty($files)){
               $folder->delete();
            }
        }
        /* Delete upper level folders if they are empty */ 
        $folder->cd(WWW_ROOT.$this->p.DS.$path);
        $contents = $folder->read(true, true);
        $folders = $contents[0];
        $files = $contents[1];
        if(empty($folders) && empty($files)){
            $folder->delete();
        }
        $folder->cd(WWW_ROOT.$this->p.DS.$path.DS.'..');
        $contents = $folder->read(true, true);
        $folders = $contents[0];
        $files = $contents[1];
        if(empty($folders) && empty($files)){
            $folder->delete();
        }
        $folder->cd(WWW_ROOT.$this->p.DS.$path.DS.'..'.DS.'..');
        $contents = $folder->read(true, true);
        $folders = $contents[0];
        $files = $contents[1];
        if(empty($folders) && empty($files)){
            $folder->delete();
        }
    }
    /**
     * Resizes and saves a Photo to the filesystem
     * 
     * @param WideImage $image Photo to be saved to the filesystem
     * @param string $path Path to save the Photo to
     * @param string $file_name Filename to use when saving the Photo
     * @param string $extension Defaults to null but allows the extension to be 
     * passed in separately from the file name.
     * 
     * @return array width and height of image
     */
    protected function _processFiles($image, $path, $file_name, $extension = null){
        if($extension === null){
            $name = $file_name;
        }
        else{
            $name = $file_name.'.'.$extension;
        }
        // Original
        $image->saveToFile(WWW_ROOT.$this->p.DS.$path.DS.$this->o.DS.$name);
        /* Destroy the image and re-create it because it has transparency from the rotation */
        $image->destroy();
        $image = WideImage::loadFromFile(WWW_ROOT.$this->p.DS.$path.DS.$this->o.DS.$name);
        // Xtra Small
        $new_image = $image->resizeDown($this->xsx, $this->xsy);
        $new_image->saveToFile(WWW_ROOT.$this->p.DS.$path.DS.$this->xs.DS.$name);
        $new_image->destroy();
        // Small
        $new_image = $image->resizeDown($this->sx, $this->sy);
        $new_image->saveToFile(WWW_ROOT.$this->p.DS.$path.DS.$this->s.DS.$name);
        $new_image->destroy();
        // Large
        $new_image = $image->resizeDown($this->lx, $this->ly);
        $new_image->saveToFile(WWW_ROOT.$this->p.DS.$path.DS.$this->l.DS.$name);
        /* Set the width and height for large size images */
        $this->lwidth = $new_image->getWidth();
        $this->lheight = $new_image->getHeight();
        $new_image->destroy();
        return array('width'=>$image->getWidth(), 'height'=>$image->getHeight());
    }
    /**
     * Extracts the GPS data from the GPS EXIF array and processes the location 
     * of the Photo if the GPS data is not empty.  
     * 
     * @param array $gps GPS Data from EXIF
     * 
     * @return string geocoded location
     */
    protected function _processGPS($gps){
        $location = null;
        /* if we don't have a proper coordinate set just return */
        if(empty($gps["GPSLongitude"]) || empty($gps['GPSLongitudeRef']) || 
                empty($gps["GPSLatitude"]) || empty($gps['GPSLatitudeRef'])){
            return $location;
        }
        
        $lat = $this->_getGps($gps["GPSLatitude"], $gps['GPSLatitudeRef']);
        $lng = $this->_getGps($gps["GPSLongitude"], $gps['GPSLongitudeRef']);
        
        if(is_float($lat) && is_float($lng)){
            $geo = new Geocoder();
            $geo->latlng = "{$lat},{$lng}";
            $xml = $geo->getFirst();
            if($xml->status == 'OK'){
                $location = $xml->formatted_address;
            }
        }
        return $location;
    }
    /**
     * @param string $exifCoord Coordinate
     * @param string $hemi Hemisphere
     * @see http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data 
     * 
     * @return GPS coordinate to be used with Google's geocoding service
     */
    protected function _getGps($exifCoord, $hemi){

        $degrees = count($exifCoord) > 0 ? $this->_gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->_gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->_gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }
    /**
     * Utility function for GPS
     * @param string $coordPart Coordinate
     * @see http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data 
     * 
     * @return GPS coordinate to be used with Google's geocoding service
     */
    protected function _gps2Num($coordPart){

        $parts = explode('/', $coordPart);

        if(count($parts) <= 0)
            return 0;

        if(count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }
    /**
     * Rotates a Photo
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param integer $id ID of the Photo to rotate
     * @param string $direction c || cc (Clockwise or Counter clockwise)
     * 
     * @return boolean
     */
    public function rotate(Model $model, $id, $direction = 'c'){
        $model->recursive = -1;
        $model->id = $id;
        $photo = $model->read(array('id', 'file_path', 'file_name'));
        try{
            $image = WideImage::loadFromFile(
                WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->o.DS.$photo['Photo']['file_name']);
        }
        catch(Exception $e){
            throw new HttpException(__('Unable to load the photo'), 500);
        }
        if($direction == 'c'){
            $image = $image->rotate(90);
        }
        else{
            $image = $image->rotate(-90);
        }
        $dim = $this->_processFiles($image, $photo['Photo']['file_path'], $photo['Photo']['file_name']);
        $d = array();
        $d['Photo']['width'] = $dim['width'];
        $d['Photo']['height'] = $dim['height'];
        $image->destroy();
        $d['Photo']['hash'] = hash_file('sha512', 
            WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->o.DS.$photo['Photo']['file_name']);
        if($model->save($d, false)){
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * Crops a Photo
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param integer $id ID of the Photo to rotate
     * @param integer $x X coordinate
     * @param integer $y Y coordinate
     * @param integer $w Width
     * @param integer $h Height
     * 
     * @return boolean
     */  
    public function crop(Model $model, $id, $x, $y, $w, $h){
        $model->recursive = -1;
        $model->id = $id;
        $photo = $model->read(array('id', 'file_path', 'file_name'));
        try{
            $image = WideImage::loadFromFile(
                WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->o.DS.$photo['Photo']['file_name']);
        }
        catch(Exception $e){
            throw new HttpException(__('Unable to load the photo'), 500);
        }
        $image = $image->crop($x, $y, $w, $h);
        $dim = $this->_processFiles($image, $photo['Photo']['file_path'], $photo['Photo']['file_name']);
        $d = array();
        $d['Photo']['width'] = $dim['width'];
        $d['Photo']['height'] = $dim['height'];
        $image->destroy();
        $d['Photo']['hash'] = hash_file('sha512', 
            WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->o.DS.$photo['Photo']['file_name']);
        if($model->save($d, false)){
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * Deletes a Photo
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param integer $id ID of the Photo to delete
     * 
     * @return boolean
     */
    public function remove(Model $model, $id){
        $model->recursive = -1;
        $model->id = $id;
        $photo = $model->read(array('id', 'file_path', 'file_name'));
        $file = new File(WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->xs.DS.$photo['Photo']['file_name']);
        if($file->exists()){
            $file->delete();
        }
        $file = new File(WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->s.DS.$photo['Photo']['file_name']);
        if($file->exists()){
            $file->delete();
        }
        $file = new File(WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->l.DS.$photo['Photo']['file_name']);
        if($file->exists()){
            $file->delete();
        }
        $file = new File(WWW_ROOT.$this->p.DS.$photo['Photo']['file_path'].DS.$this->o.DS.$photo['Photo']['file_name']);
        if($file->exists()){
            $file->delete();
        }
        $this->_removeEmptyDirs($photo['Photo']['file_path']);
        $model->Metadata->deleteAll(array('photo_id'=>$id));
        return $model->delete($id);
    }
    /**
     * Removes empty directories
     * 
     * @param Model $model Model automatically passed into the behavior
     * @param string $path Path to be deleted
     * 
     * @return void
     */
    public function removeDirs(Model $model, $path){
        $this->_removeEmptyDirs($path);
    }
}