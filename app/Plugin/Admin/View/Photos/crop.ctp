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
$this->Html->script('jquery.jcrop.min', array('inline'=>false));
$this->Html->css('jquery.jcrop.min', null, array('inline'=>false));
$this->Html->addCrumb(h($photo['Photo']['title']), array('action'=>'view', h($photo['Photo']['id'])));
$this->Html->addCrumb(__('Crop'));
?>
<div class="crop-container"<?php if(isset($photo['Photo']['width'])){echo 'style="width:'.h($photo['Photo']['width']).'px;"';}?>>
    <?php
    echo $this->Form->create();
    echo $this->Form->submit(__('Crop'), array('class'=>'btn btn-primary'));
    ?>
    <div class="crop">
        <?php $url = h($this->X2->photoUrl().$photo['Photo']['file_path'].'/'.
                Configure::read('X2.Dir.O').'/'.$photo['Photo']['file_name']);
        echo $this->Html->image($url, array('id'=>'crop-photo'));
        ?>
    </div>
    <?php
    echo $this->Form->hidden('x', array('id'=>'x'));
    echo $this->Form->hidden('y', array('id'=>'y'));
    echo $this->Form->hidden('w', array('id'=>'w'));
    echo $this->Form->hidden('h', array('id'=>'h'));
    echo $this->Form->submit(__('Crop'), array('class'=>'btn btn-primary'));
    echo $this->Form->end();
    ?>
</div>
<script type="text/javascript">
    $(function(){
        $('#crop-photo').Jcrop({
            onChange: prepareCrop,
            onSelect: prepareCrop
        });
    });
    function prepareCrop(c){
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };
</script>