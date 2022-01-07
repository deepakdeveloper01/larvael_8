<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\CmsPageContentType;
use common\models\CmsPageAdditionalContent;
/**
 * @var $model \common\models\CmsPage
 * @var $this yii\web\View
 */
?>

<div class="template-boxes">

    <h1>
        <?= Html::encode($model->title) ?>
    </h1>

    <?php if (null !== $model->imageDocumentId) : ?>
        <img src="<?= Url::to(['/document/download', 'publicId' => $model->imageDocument->publicId]) ?>"
             class="img-responsive"
        >
    <?php endif; ?>

    <div class="introduction-text">
        <?= $model->introduction ?>
    </div>

    <div class="dynamic-content">
        <div class="row">
            <?php
            /** @var \common\models\CmsPageContent[] $cmsPageContents */
            $cmsPageContents = $model->getActivePageContent()->all();
            $count = count($cmsPageContents);

            $cmsPageContentSlice1 = [];
            $cmsPageContentSlice2 = [];
            $cmsPageContentSlice3 = [];

            //Yes this is hacky
            foreach ($cmsPageContents as $key => $cmsPageContentItem) {
                switch ($key % 3) {
                    case 0:
                        $cmsPageContentSlice1[] = $cmsPageContentItem;
                        break;
                    case 1:
                        $cmsPageContentSlice2[] = $cmsPageContentItem;
                        break;
                    case 2:
                        $cmsPageContentSlice3[] = $cmsPageContentItem;
                        break;
                }
            }
            ?>

            <div class="col-md-4">
                <?php foreach ($cmsPageContentSlice1 as $cmsPageContent) : ?>
                    <?= $this->render(
                        '//cms/groups/' . $cmsPageContent->cmsGroupContent->cmsGroup->safeName,
                        ['cmsPageContent' => $cmsPageContent]
                    ) ?>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <?php foreach ($cmsPageContentSlice2 as $cmsPageContent) : ?>
                    <?= $this->render(
                        '//cms/groups/' . $cmsPageContent->cmsGroupContent->cmsGroup->safeName,
                        ['cmsPageContent' => $cmsPageContent]
                    ) ?>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <?php foreach ($cmsPageContentSlice3 as $cmsPageContent) : ?>
                    <?= $this->render(
                        '//cms/groups/' . $cmsPageContent->cmsGroupContent->cmsGroup->safeName,
                        ['cmsPageContent' => $cmsPageContent]
                    ) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
   
    <?php foreach ($model->getCmsPageAddtionalContents()->orderBy('`order`')->all() as $articleContent) {

                switch ($articleContent->contentType->name) {
                    case CmsPageContentType::TEXT:
                        echo $this->render(
                            '//cms/_text_block',
                            ['timestamp' => $articleContent->order, 'content' => $articleContent->content]
                        );
                        break;
                    case CmsPageContentType::VIDEO:
                        echo $this->render(
                            '//cms/_video_block',
                            ['timestamp' => $articleContent->order, 'content' => CmsPageAdditionalContent::embedLink($articleContent->content)]
                        );
                       // echo $articleContent->content;
                        break;
                    case CmsPageContentType::IMAGE:
                        echo $this->render(
                            '//cms/_image_block',
                            ['timestamp' => $articleContent->order, 'content' => $articleContent->content]
                        );
                        break;
                }
            } ?>
     <p class="margin-top-20">
        <strong>Categories: </strong>
        <?= implode(', ', array_map(function ($type) {
            return $type->name;
        }, $model->cmsCategories)) ?>
    </p>
          
</div>
