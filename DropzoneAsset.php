<?php

/**
 * Dropzone.js Asset
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see http://www.dropzonejs.com/
 */

namespace xj\dropzone;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle {

    public $sourcePath = '@bower/dropzone/dist';
    public $css = [];
    public $js = ['dropzone.js'];
    public $depends = ['yii\web\JqueryAsset'];

}
