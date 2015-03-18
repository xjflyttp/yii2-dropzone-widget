# yii2-dropzone-widget
yii2-dropzone-widget http://www.dropzonejs.com/

composer.json
-----
```json
"require": {
    "xj/yii2-dropzone-widget": "*"
},
```

Action:
----
```php
public function actions() {
return [
    'upload' => [
        'class' => \xj\dropzone\UploadAction::className(),
        'uploadBasePath' => '@webroot/attachment/album', //file system path
        'uploadBaseUrl' => '@web/attachment/album', //web path
//        'format' => '{yyyy}{mm}{dd}/{time}{rand:6}', // OR Closure
        'format' => function(UploadAction $action) {
            $fileext = $action->uploadFileInstance->getExtension();
            $filehash = sha1(uniqid() . time());
            $p1 = substr($filehash, 0, 2);
            $p2 = substr($filehash, 2, 2);
            return "{$p1}/{$p2}/{$filehash}.{$fileext}";
        },
        'validateOptions' => [
            'extensions' => ['jpg', 'png'],
            'maxSize' => 1 * 1024 * 1024, //file size
        ],
        'beforeValidate' => function($action) {
            $id = Yii::$app->request->get('id');
            if ($id === null) {
                throw new \yii\base\Exception('错误的ID');
            }
            if (Something::findOne($id) === null) {
                throw new \yii\base\Exception('错误的ID');
            }
        },
        'afterValidate' => function($action) {
            //something
        },
        'beforeSave' => function($action) {
            //something
        },
        'afterSave' => function($action) {
            /* @var $action xj\uploadify\UploadAction */
//resize image
            $id = Yii::$app->request->get('id');
            $srcFilename = $action->getUploadFileInstance()->name;
            $image = \xj\kohanaimage\Image::load($action->fullFilename);
            if ($image->width > 1280) {
                $image->resize(1280, NULL)->save();
            }
//insert image to Tables
            $photoModel = Photo::addByImage($action->fullFilename, $action->filename, $id, $srcFilename);
            $action->output['id'] = $photoModel->id;
        },
    ],
];
}
```

View
---
```php
Dropzone::widget([
    'url' => ['upload', 'id' => $model->id],
    'id' => 'album-upload',
    'jsOptions' => [
        'previewTemplate' => '<div class="dz-size" data-dz-size></div>',
        'success' => new JsExpression(<<<EOF
function(file, data) {
    console.log(data);
    if (data.error) {
        alert(data.msg);
    } else {
        alert(data.fileUrl);
        alert(data.id);
//etc...
        console.log(file);
    }
}
EOF
),
    ],
    'warpOptions' => ['id' => 'album-upload-dropzone'],
    'formOptions' => ['class' => 'album-upload-dropzone dz-clickable'],
]);
```
