<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php
if($this->Session->read('Auth.User.admin') !== true && Configure::read('debug') == 0):
    throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>
<?php
if(Configure::read('debug') > 0):
    Debugger::checkSecurityKeys();
endif;
?>
<p>
    <?php
    if(version_compare(PHP_VERSION, '5.2.8', '>=')):
        echo '<span class="notice success">';
        echo __('Your version of PHP is 5.2.8 or higher.');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('Your version of PHP is too low. You need PHP 5.2.8 or higher to use X2.');
        echo '</span>';
    endif;
    ?>
</p>
<p>
    <?php
    if(is_writable(TMP)):
        echo '<span class="notice success">';
        echo __('Your tmp directory is writable.');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('Your tmp directory is NOT writable.');
        echo '</span>';
    endif;
    ?>
</p>
<p>
    <?php
    $settings = Cache::settings();
    if(!empty($settings)):
        echo '<span class="notice success">';
        echo __('The %s is being used for core caching. To change the config edit APP/Config/core.php ', '<em>'.$settings['engine'].'Engine</em>');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('Your cache is NOT working. Please check the settings in APP/Config/core.php');
        echo '</span>';
    endif;
    ?>
</p>
<p>
    <?php
    $filePresent = null;
    if(file_exists(APP.'Config'.DS.'database.php')):
        echo '<span class="notice success">';
        echo __('Your database configuration file is present.');
        $filePresent = true;
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('Your database configuration file is NOT present.');
        echo '<br/>';
        echo __('Rename APP/Config/database.php.default to APP/Config/database.php');
        echo '</span>';
    endif;
    ?>
</p>
<?php
if(isset($filePresent)):
    App::uses('ConnectionManager', 'Model');
    try{
        $connected = ConnectionManager::getDataSource('default');
    }
    catch(Exception $connectionError){
        $connected = false;
    }
    ?>
    <p>
        <?php
        if($connected && $connected->isConnected()):
            echo '<span class="notice success">';
            echo __('X2 is able to connect to the database.');
            echo '</span>';
        else:
            echo '<span class="notice">';
            echo __('X2 is NOT able to connect to the database.');
            echo '<br /><br />';
            echo $connectionError->getMessage();
            echo '</span>';
        endif;
        ?>
    </p>
<?php endif; ?>
<p>
    <?php
    if(extension_loaded('fileinfo')):
        echo '<span class="notice success">';
        echo __('Mime type detection is enabled.');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('Mime type detection is not enabled. To enable it please see http://www.php.net/manual/en/fileinfo.installation.php');
        echo '</span>';
    endif;
    ?>
</p>
<p>
    <?php
    if(extension_loaded('exif')):
        echo '<span class="notice success">';
        echo __('EXIF metadata parsing is enabled.');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('EXIF metadata parsing is not enabled. To enable it please see http://www.php.net/manual/en/exif.installation.php');
        echo '</span>';
    endif;
    ?>
</p>
<p>
    <?php
    if(extension_loaded('gd')):
        echo '<span class="notice success">';
        echo __('The GD extension is enabled.');
        echo '</span>';
    else:
        echo '<span class="notice">';
        echo __('GD is not enabled. To enable it please see http://www.php.net/manual/en/image.installation.php');
        echo '</span>';
    endif;
    ?>
</p>
<?php
App::uses('Validation', 'Utility');
if(!Validation::alphaNumeric('cakephp')){
    echo '<p><span class="notice">';
    echo __('PCRE has not been compiled with Unicode support.');
    echo '<br/>';
    echo __('Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
    echo '</span></p>';
}
?>