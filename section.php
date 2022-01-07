<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\CmsItem;

use yii\db\Query;
use common\models\CmsPageContentType;
use common\models\CmsPageAdditionalContent;
/**
 * @var $cmsPageContent \common\models\CmsPageContent
 * @var $this yii\web\View
 */
?>

<div class="row">
    <div class="col-md-12">
        <div class="section-header">
            <h3>
            <?= $cmsPageContent->getItemContent(CmsItem::SECTION_TITLE) ?>
            </h3>
            <div class="collapse-arrow collapsed" data-collapse="section-collapse-<?= $cmsPageContent->id ?>">
                <span class="fa fa-chevron-down"></span>
            </div>
        </div>
    </div>
</div>
<div id="section-collapse-<?= $cmsPageContent->id ?>" class="collapsible isCollapsed">
    <?= $cmsPageContent->getItemContent(CmsItem::SECTION_TEXT) ?>
   <?php 
   //echo "cmsPageContent---------".$cmsPageContent->id;
    if(!empty($cmsPageContent->id)){
        // check in table
        $checkExists = new Query;
        $checkExists ->select(['cms_page_concertina_additonal_content.*'])
            ->from('cms_page__content')
            ->join('LEFT JOIN ','cms_group__content', 'cms_group__content.id = cms_page__content.cmsGroupContentId')      
            ->join('LEFT JOIN', 'cms_item__content', 'cms_item__content.cmsGroupContentId =cms_page__content.cmsGroupContentId')

             ->join('LEFT JOIN', 'cms_page_concertina_additonal_content', 'cms_page_concertina_additonal_content.cms_item_content_id =cms_item__content.id')
           
           // ->andWhere(['cms_item__content.cmsPageId'=> $model->id])
            ->andWhere(['cms_page__content.cmsPageId'=> $model->id,'cms_page__content.cmsGroupContentId'=>$cmsPageContent->cmsGroupContentId])
            ->orderBy('cms_page_concertina_additonal_content.order ASC')
            ->all(); 
        $command = $checkExists->createCommand();
        $data = $command->queryAll();               
        if(!empty($data)){
            foreach ($data as $key => $value) {
               // echo '$value->content_type_id'.$value['content_type_id'];
                switch ($value['content_type_id']) {
                    case '1':
                    echo $this->render(
                        '//cms/_text_block',
                        ['timestamp' => $value['order'], 'content' => $value['content']]
                    );
                    break;
                    case '2':
                        echo $this->render('//cms/_video_block',
                        ['timestamp' => $value['order'], 'content' => CmsPageAdditionalContent::embedLink($value['content'])]
                        );
                  
                    break;
                    case '3':
                        echo $this->render('//cms/_image_block',
                        ['timestamp' => $value['order'], 'content' => $value['content']]
                    );
                    break;
                }
            }
        }
    }
   ?>
</div>

<?php //echo "string <pre>"; print_r($cmsPageContent); ?>
