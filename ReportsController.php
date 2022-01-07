<?php

namespace backend\controllers;


use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\AuthItem;
use common\models\ReportPreference;
use common\models\ReportStatus;
use backend\models\ReportPreferenceForm;
use backend\models\ReportPreferenceSearch;
use backend\models\RetailPartnerContactPreferenceForm;
use backend\models\RetailPartnerContactPreferenceSearch;
use backend\models\RetailPartnerContactsPreferenceForm;
use backend\models\RetailPartnerContactsPreferenceSearch;
use common\models\RetailPartnerReportPreference;
use backend\models\RetailPartnerReportPreferenceForm;
use backend\models\RetailPartnerReportPreferenceSearch;
use common\models\Member;
use common\models\Branch;
use yii\web\ErrorAction;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use common\models\MemberType;
use common\models\AdditionalMemberType1;
use common\models\AdditionalMemberType2;
use common\models\RetailPartnerContactPreference;
use common\models\BusinessPartnerContact;
use common\models\RetailPartnerContactsPreference;
use common\models\BusinessPartner;
use common\models\BusinessPartnerClicks;

/**
 * Report controller
 */
class ReportsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [AuthItem::ROLE_ADMIN],
                    ],
                    [
                        'allow'       => true,
                        'actions'     => ['index', 'RetailPartner', 'view', 'RetailPartnerView', 'search-list', 'export', 'ExportRetailPartner', 'change-order', 'RetailPartnerContacts', 'RetailPartnerContactsView', 'ExportRetailPartnerContacts', 'RetailPartnerContact', 'RetailPartnerContactView', 'ExportRetailPartnerContact'],
                        'permissions' => [
                            AuthItem::PERMISSION_CAN_VIEW_REPORTS,
                        ],
                    ],
                    [
                        'allow'       => true,
                        'actions'     => [
                            'update',
                            'create',
                            'delete',
                            'change-order',
                            'export',
                            'ExportRetailPartner',
                            'CreateRetailPartnerReport',
                            'RetailPartnerUpdate',
                            'RetailPartnerDelete',
                            'ExportRetailPartnerContacts',
                            'CreateRetailPartnerContacts',
                            'RetailPartnerContactsUpdate',
                            'RetailPartnerContactsDelete',
                            'ExportRetailPartnerContact',
                            'CreateRetailPartnerContact',
                            'RetailPartnerContactUpdate',
                            'RetailPartnerContactDelete'
                        ],
                        'permissions' => [
                            AuthItem::PERMISSION_CAN_CREATE_UPDATE_REPORTS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class'  => ErrorAction::class,
                'layout' => \Yii::$app->user->isGuest ? 'guest' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ReportPreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRetailPartnerReportList()
    {
        $searchModel = new ReportPreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('retail-partner-report-list', [
            'searchModel'  => $searchModel ?? null,
            'dataProvider' => $dataProvider ?? null,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\HttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $columnNames = $this->getColumns();

        return $this->render('view', [
            'model'               => $model,
            'columnNames'       => $columnNames
        ]);
    }

    public function actionCreate()
    {
        $model = new ReportPreferenceForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate(['name', 'columns']) && $model->save()) {
            return $this->redirect(['index']);
        }

        $columns = $this->getColumns();
        return $this->render('create', ['model' => $model, 'columns' => $columns]);
    }

    /**
     * Updates an existing ReportPreference model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $columns = $this->getColumns();
        $reportPreference = $this->findModel($id);
        $reportPreferenceForm = new ReportPreferenceForm();
        $reportPreferenceForm->setReportPreference($reportPreference);
        if ($reportPreferenceForm->load(Yii::$app->request->post())) {
            if ($reportPreferenceForm->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model'  => $reportPreferenceForm,
            'reportPreference' => $reportPreference,
            'columns' => $columns
        ]);
    }

    public function actionSave()
    {
        $reportName = Yii::$app->request->post('name');
        $columns = Yii::$app->request->post('fields');

        $model = new ReportPreference();

        if ($model->validate()) {
            $model->name = $reportName;
            $model->columns = implode(",", $columns);
            $model->save();
            return $this->redirect(['index']);
        } else {

            \Yii::$app->getSession()->setFlash('error', 'Please fill all fields');
            $this->redirect(['create']);
        }
    }

    protected function getAlias($val)
    {
        return $val->alias;
    }

    public function actionExport()
    {
        $preference = ReportPreference::findOne(Yii::$app->request->get('id'));
        $columnsToBeExported = isset($preference) ? explode(",", $preference->columns) : [];
        $members = Member::find()->all();
        $reportStatus = ReportStatus::find()->where(['in', 'alias', $columnsToBeExported])->orderBy('sort_order', SORT_ASC)->all();

       $sql = "SELECT *
                FROM branch 
                LEFT JOIN address ON address.id = branch.addressId
                WHERE NOT EXISTS (
                    SELECT *
                    FROM member__branch 
                    WHERE member__branch.branchId = branch.id)";

        $notAssociatedBranch = Yii::$app->db->createCommand($sql)->queryAll();

      


        // echo "<pre>"; print_r($notAssociatedBranch); die;


        $values = [];
        foreach ($members as $member) {
            $temp = [];

            

             if($member->id == 0){
           
                   for ($i = 0; $i < count($reportStatus); $i++) {
                        if ($reportStatus[$i]->alias == 'memberNumber') {
                            $temp[] = array_map(function($item) { return $item->publicId;
                                    },$member->addressContacts);
                       } else if ($reportStatus[$i]->alias == 'memberName') {
                            $temp[] = array_map(function($item) { return $item->nameInCorrespondence;
                                    },$member->addressContacts);
                       } else if ($reportStatus[$i]->alias == 'title') {
                            $temp[] = array_map(function($item) { return $item->title;
                                    },$member->addressContacts);
                       } 
                        else if ($reportStatus[$i]->alias == 'forename') {
                            $temp[] = array_map(function($item) { return $item->forename;
                                    },$member->addressContacts);
                       } 
                        else if ($reportStatus[$i]->alias == 'surname') {
                            $temp[] = array_map(function($item) { return $item->surname;
                                    },$member->addressContacts);
                       } 
                        else if ($reportStatus[$i]->alias == 'addressLine1') {
                            $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->addressLine1) : 'NA';
                            },$member->addressContacts);
                       } 
                        else if ($reportStatus[$i]->alias == 'addressLine2') {
                            $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->addressLine2) : 'NA';
                            },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'addressLine3') {
                             $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->addressLine3) : 'NA';
                            },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'town') {
                             $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->town) : 'NA';
                            },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'county') {
                             $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->county) : 'NA';
                            },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'postcode') {
                             $temp[] = array_map(function($addressContacts){
                                return ($addressContacts->address) ? str_replace(",", "_", $addressContacts->address->postcode) : 'NA';
                            },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'phoneNumber') {
                             $temp[] = array_map(function($item) { return $item->telephone;
                                    },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'mobilePhoneNumber') {
                            $temp[] = array_map(function($item) { return $item->mobile;
                                    },$member->addressContacts);
                       } 

                       else if ($reportStatus[$i]->alias == 'email') {
                            $temp[] = array_map(function($item) { return $item->email;
                                    },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'nfspJournal') {
                            $temp[] = array_map(function($item) { return $item->magazineCount;
                                    },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'jobTitle') {
                            $temp[] = array_map(function($item) { return $item->jobTitle;
                                    },$member->addressContacts);
                       } 
                       else if ($reportStatus[$i]->alias == 'businessName') {
                            $temp[] = array_map(function($item) { return $item->stakeholder->businessName;
                                    },$member->addressContacts);
                       } 
                        else {
                            $temp[] = array_map(function($item) { return 'N/A';
                                    },$member->addressContacts);
                        }
                     
                  }
                 


                

            }

            else if($member->id == 1){
          
                   for ($i = 0; $i < count($reportStatus); $i++) {
                        if ($reportStatus[$i]->alias == 'memberNumber') {
                            $temp[] = array_map(function($item) { return $item->publicId;
                                    },$member->businessPartners);
                       }  
                        else if ($reportStatus[$i]->alias == 'addressLine1') {
                            $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->addressLine1) : 'NA';
                            },$member->businessPartners);
                       } 
                        else if ($reportStatus[$i]->alias == 'addressLine2') {
                            $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->addressLine2) : 'NA';
                            },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'addressLine3') {
                             $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->addressLine3) : 'NA';
                            },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'town') {
                             $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->town) : 'NA';
                            },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'county') {
                             $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->county) : 'NA';
                            },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'postcode') {
                             $temp[] = array_map(function($businessPartners){
                                return ($businessPartners->address) ? str_replace(",", "_", $businessPartners->address->postcode) : 'NA';
                            },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'phoneNumber') {
                             $temp[] = array_map(function($item) { return $item->officePhoneNumber;
                                    },$member->businessPartners);
                       } 

                       else if ($reportStatus[$i]->alias == 'email') {
                            $temp[] = array_map(function($item) { return $item->email;
                                    },$member->businessPartners);
                       } 
                       else if ($reportStatus[$i]->alias == 'nfspJournal') {
                            $temp[] = array_map(function($item) { return $item->magazineCount;
                                    },$member->businessPartners);
                       } 
                       // else if ($reportStatus[$i]->alias == 'jobTitle') {
                       //      $temp[] = array_map(function($item) { return $item->jobTitle;
                       //              },$member->addressContacts);
                       // } 
                       else if ($reportStatus[$i]->alias == 'businessName') {
                            $temp[] = array_map(function($item) { return $item->name;
                                    },$member->businessPartners);
                       } 
                        else {
                            $temp[] = array_map(function($item) { return 'N/A';
                                    },$member->businessPartners);
                        }
                     
                  }
                 


                

            }

            else if(empty($member->branches)){

                for ($i = 0; $i < count($reportStatus); $i++) {
                    if ($reportStatus[$i]->alias == 'nfspRegionName') {
                        $result = $member->memberTypeLinks[0]->region->parent ? $member->memberTypeLinks[0]->region->parent->name : $member->memberTypeLinks[0]->region->name;
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'nfspRegionNumber') {
                        $result = $member->memberTypeLinks[0]->region->parent ? $member->memberTypeLinks[0]->region->parent->number : $member->memberTypeLinks[0]->region->number;
                        $temp[] = $result;
                    }

                    else if ($reportStatus[$i]->alias == 'nfspBranchNumber') {
                        $result = !empty($member->memberTypeLinks[0]->region->parent && $member->memberTypeLinks[0]->region) ? $member->memberTypeLinks[0]->region->number : 'NA';
                        $temp[] = $result;
                    }

                    else if ($reportStatus[$i]->alias == 'nfspBranchName') {
                        $result = !empty($member->memberTypeLinks[0]->region->parent && $member->memberTypeLinks[0]->region) ? $member->memberTypeLinks[0]->region->name : 'NA';
                        $temp[] = $result;
                    }

                     else if ($reportStatus[$i]->alias == 'addressLine1') {
                        $result = $member->address ? $member->address->addressLine1 : 'NA';
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'addressLine2') {
                        $result = $member->address ? $member->address->addressLine2 : 'NA';
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'addressLine3') {
                        $result = $member->address ? $member->address->addressLine3 : 'NA';
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'town') {
                        $result = $member->address ? $member->address->town : 'NA';
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'county') {
                        $result = $member->address ? $member->address->county : 'NA';
                        $temp[] = $result;
                    } 

                    else if ($reportStatus[$i]->alias == 'postcode') {
                        $result = $member->address ? $member->address->postcode : 'NA';
                        $temp[] = $result;
                    } 

                    else if ($reportStatus[$i]->alias == 'nfspJournal') {
                        $result = $member->magazineCount ? $member->magazineCount : 'NA';
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'regionalSecretaryName') {
                        $result = $this::getMemberNameByTypeForNoBranch($member, MemberType::REGIONAL_SECRETARY);
                        $temp[] = $result;
                    } else if ($reportStatus[$i]->alias == 'regionalTreasurerName') {
                        $result = $this::getMemberNameByTypeForNoBranch($member, MemberType::REGIONAL_TREASURER);
                        $temp[] = $result;
                    } else if ($reportStatus[$i]->alias == 'nfspBranchSecretaryName') {
                        $result = $this::getBranchMemberNameByTypeForNoBranch($member, MemberType::BRANCH_SECRETARY);
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'nfspDirector') {
                        $result = $this::getMemberNameByTypeForNoBranch($member, MemberType::COUNCIL_MEMBER);
                        $temp[] = $result;
                    } 
                    else {
                        eval($reportStatus[$i]->value);
                    }
                }

            }

             else {

                for ($i = 0; $i < count($reportStatus); $i++) {
                    if ($reportStatus[$i]->alias == 'regionalSecretaryName') {
                        $result = $this::getMemberNameByType($member, MemberType::REGIONAL_SECRETARY);
                        $temp[] = $result;
                    } else if ($reportStatus[$i]->alias == 'regionalTreasurerName') {
                        $result = $this::getMemberNameByType($member, MemberType::REGIONAL_TREASURER);
                        $temp[] = $result;
                    } else if ($reportStatus[$i]->alias == 'nfspBranchSecretaryName') {
                        $result = $this::getBranchMemberNameByType($member, MemberType::BRANCH_SECRETARY);
                        $temp[] = $result;
                    } 
                    else if ($reportStatus[$i]->alias == 'nfspDirector') {
                        $result = $this::getMemberNameByType($member, MemberType::COUNCIL_MEMBER);
                        $temp[] = $result;
                    } 
                    else {
                        eval($reportStatus[$i]->value);
                    }
                }

            }


            $values[] = $temp;
        }

        if(isset($notAssociatedBranch) && !empty($notAssociatedBranch)){
            $values1 = [];
            // echo "<pre>"; print_r($notAssociatedBranch); die;
            //foreach ($notAssociatedBranch as $key => $branchData) {
            $temp1 = [];
                for ($i = 0; $i < count($reportStatus); $i++) {
                      if ($reportStatus[$i]->alias == 'memberNumber') {
                            $temp1[] = array_map(function($item) { return $item['publicId'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'FAD') {
                            $temp1[] = array_map(function($item) { return $item['FAD'];
                                    },$notAssociatedBranch);
                       } else if ($reportStatus[$i]->alias == 'postofficename') {
                            $temp1[] = array_map(function($item) { return $item['officeName'];
                                    },$notAssociatedBranch);
                       }  
                        else if ($reportStatus[$i]->alias == 'addressLine1') {
                            $temp1[] = array_map(function($item) { return $item['addressLine1'];
                                    },$notAssociatedBranch);
                       } 
                        else if ($reportStatus[$i]->alias == 'addressLine2') {
                            $temp1[] = array_map(function($item) { return $item['addressLine2'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'addressLine3') {
                            $temp1[] = array_map(function($item) { return $item['addressLine3'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'town') {
                             $temp1[] = array_map(function($item) { return $item['town'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'county') {
                            $temp1[] = array_map(function($item) { return $item['county'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'postcode') {
                             $temp1[] = array_map(function($item) { return $item['postcode'];
                                    },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'phoneNumber') {
                             $temp1[] = array_map(function($item) { return $item['phoneNumber'];
                                    },$notAssociatedBranch);
                       } 
                        

                       else if ($reportStatus[$i]->alias == 'contractType') {
                            $temp1[] = array_map(function($branch){
                                return ($branch->contractType) ? str_replace(",", "_", $branch->contractType->name) : 'NA';
                            },$notAssociatedBranch);
                       } 
                       else if ($reportStatus[$i]->alias == 'nfspJournal') {
                            $temp1[] = array_map(function($item) { return $item['copiesOfMagazine'];
                                    },$notAssociatedBranch);
                       } 
                       
                       else if ($reportStatus[$i]->alias == 'businessName') {
                            $temp1[] = array_map(function($item) { return $item['branchName'];
                                    },$notAssociatedBranch);
                       } 

                      
                       else {
                             $temp1[] = array_map(function($item) { return 'N/A';
                                    },$notAssociatedBranch);
                        }

                    } 

                    $values1[] = $temp1; 
                //}
            } else{
                $values1 = [];
            }


            

      //  new work

// echo "<pre>";
// print_r($values1);
// die;
$values = array_merge($values,$values1);

        foreach($values as $collection){
            $newArrs = array_map(function($val){
                    if(is_array($val)){
                        if(empty($val)){
                            return 'N/A';
                        }elseif(count($val) == 1){
                            return $val[0];
                        }else{
                            $newKey = ['child' => $val, 'count' => count($val)];
                            return $newKey;
                        }
                    }else{
                        return $val;
                    }
                },$collection);
                $report = array_column($newArrs, 'count');
                if(!empty($report)){
                    $maxVal = max($report);
                    $reportArr = [];
                    for ($x = 1; $x <= $maxVal; $x++) {
                        $dplk = array_map(function($currentArr) use($x){
                            if(is_array($currentArr)){
                                return $currentArr['child'][($x-1)] ?? "N/A";
                            }else{
                                return $currentArr;
                            }
                        },$newArrs);
                        $duplicate[] = $dplk;
                    }
                }else{
                    $duplicate[] = $newArrs;
                }
        }
                   

        
// echo "<pre>";
// print_r($duplicate);
// die;
           
    //    echo "<pre>";
    //                 print_r($duplicate);        

    // die;
         $results = array_map(function ($duplicate) use ($columnsToBeExported) {
            return array_combine($columnsToBeExported, $duplicate);
        }, $duplicate);
        
        $results = array_intersect_key( $results , array_unique( array_map('serialize' , $results)));
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results,
        ]);

        $exporter = new CsvGrid([
            'dataProvider' => $dataProvider,  'csvFileConfig' => [
                'enclosure' => '',
            ],
        ]);
        return $exporter->export()->send('Report-' . date('Y-m-d-G-i-s') . '.csv');
    }

    

    static private function getMemberNameByType($member, $memberType)
    {
        $memberName = 'NA';
        if (count($member->branches) > 0) {
            $data = array_map(function ($branch) use ($memberType,$member) {
              if(!empty($branch->region)){
                $dt = $branch->region->parent->getMemberTypeLinks()
                    // ->andWhere(['memberId' => $member->id])
                    // ->andWhere(['IS NOT', 'memberId', NULL])
                    ->orWhere(['memberTypeId' => MemberType::get($memberType)->id])
                    ->orWhere(['additionalMemberType1Id' => AdditionalMemberType1::get($memberType)->id])
                    ->orWhere(['additionalMemberType2Id' => AdditionalMemberType2::get($memberType)->id])
                    ->joinWith(['member'])
                    ->one();
                    // echo "<pre>"; print_r($dt);
                if ($dt && $dt->member) {
                    return !empty($dt->member->correspondenceName) ? $dt->member->correspondenceName : $dt->member->title.' '.$dt->member->forename.' '.$dt->member->surname;
                }else {
                    return 'NA';
                }
             } else { return 'NA'; }
            }, $member->branches);

            $memberName = $data;
        }

        else
        {

            $data1 = array_map(function ($memberLink) use ($memberType) {
              if(!empty($memberLink->region)){
                $dt1 = $memberLink->region->parent->getMemberTypeLinks()
                    ->where(['memberTypeId' => MemberType::get($memberType)->id])
                    ->orWhere(['additionalMemberType1Id' => AdditionalMemberType1::get($memberType)->id])
                    ->orWhere(['additionalMemberType2Id' => AdditionalMemberType2::get($memberType)->id])
                    ->joinWith(['member'])
                    ->one();
                   
                
                if ($dt1 && $dt1->member) {
                    return !empty($dt1->member->correspondenceName) ? $dt1->member->correspondenceName : $dt1->member->title.' '.$dt1->member->forename.' '.$dt1->member->surname;
                }else {
                    return 'NA';
                }
             } else { return 'NA'; }
            }, $member->memberTypeLinks);

            $memberName = $data1;
        }

        return $memberName;
    }

    static private function getBranchMemberNameByType($member, $memberType)
    {
        $memberName = 'NA';
        if (count($member->branches) > 0) {
            $data = array_map(function ($branch) use ($memberType) {
              if(!empty($branch->region)){
                $dt = $branch->region->getMemberTypeLinks()
                    ->where(['memberTypeId' => MemberType::get($memberType)->id])
                    ->orWhere(['additionalMemberType1Id' => AdditionalMemberType1::get($memberType)->id])
                    ->orWhere(['additionalMemberType2Id' => AdditionalMemberType2::get($memberType)->id])
                    ->joinWith(['member'])
                    ->one();
                if ($dt && $dt->member) {
                    return $dt->member->correspondenceName;
                }else {
                    return 'NA';
                }
             } else { return 'NA'; }
            }, $member->branches);

            $memberName = $data;
        }

        return $memberName;
    }

    static private function getMemberNameByTypeForNoBranch($member, $memberType){
       
            $data1 = array_map(function ($memberLink) use ($memberType) {
              if(!empty($memberLink->region && $memberLink->region->parent)){
                $dt1 = $memberLink->region->parent->getMemberTypeLinks()
                    ->where(['memberTypeId' => MemberType::get($memberType)->id])
                    ->orWhere(['additionalMemberType1Id' => AdditionalMemberType1::get($memberType)->id])
                    ->orWhere(['additionalMemberType2Id' => AdditionalMemberType2::get($memberType)->id])
                    ->joinWith(['member'])
                    ->one();
                   // echo "<pre>"; print_r($dt1); die;
                
                if ($dt1 && $dt1->member) {
                    return !empty($dt1->member->correspondenceName) ? $dt1->member->correspondenceName : $dt1->member->title.' '.$dt1->member->forename.' '.$dt1->member->surname;
                }else {
                    return 'NA';
                }
             } else { 
                return 'NA';
              }
            }, $member->memberTypeLinks);

            $memberName = $data1;

         
            return $memberName;
    }

    static private function getBranchMemberNameByTypeForNoBranch($member, $memberType)
    {
        $memberName = 'NA';
            $data = array_map(function ($branch) use ($memberType) {
              if(!empty($branch->region)){
                $dt = $branch->region->getMemberTypeLinks()
                    ->where(['memberTypeId' => MemberType::get($memberType)->id])
                    ->orWhere(['additionalMemberType1Id' => AdditionalMemberType1::get($memberType)->id])
                    ->orWhere(['additionalMemberType2Id' => AdditionalMemberType2::get($memberType)->id])
                    ->joinWith(['member'])
                    ->one();
                if ($dt && $dt->member) {
                    return $dt->member->correspondenceName;
                }else {
                    return 'NA';
                }
             } else { return 'NA'; }
            }, $member->memberTypeLinks);

            $memberName = $data;

        return $memberName;
    }

    /**
     * Finds the ReportPreference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReportPreference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var Branch $model */
        $model = ReportPreference::find()
            ->where(['id' => $id])
            ->one();
        if ($model !== null) {
            return $model;
        }

        throw new \NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Deletes an existing Report Preference model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    private function getColumns()
    {
        $reportStatus = ReportStatus::find()->orderBy('sort_order', SORT_ASC)->all();
        $values_data = [];
        foreach ($reportStatus as $key => $reportStatus_data) {
            $values_data[$reportStatus_data->alias] = $reportStatus_data->name;
        }

        return $values_data;
    }

    public function actionChangeOrder()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            for ($i = 0; $i < count($data['page_id_array']); $i++) {
                // $reportstatus = new ReportStatus();
                ReportStatus::updateAll(['sort_order' => $i], ['in', 'alias', $data['page_id_array'][$i]]);
            }
            //die();
        }

        /*if ($this->request->is('ajax') && $this->request->is('post')) {
            //pr($this->request->data);die;
            for($i=0; $i<count($this->request->data['page_id_array']); $i++)
            {
            $modules = TableRegistry::get('Modules');
            $query = $modules->query();
            $query->update()
                ->set(['sort_order' => $i])
                ->where(['id' => $this->request->data['page_id_array'][$i] , 'project_id' => $this->request->data['projectId'] , 'module_type_id' => $this->request->data['moduleTypeId']])
                ->execute();
            }

            $this->message = 'Module order updated';
            $this->respond();
        }*/
    }

    //retail partner report section

    public function actionRetailPartner()
    {
        $searchModel = new RetailPartnerReportPreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('retail-partner/RetailPartner', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRetailPartnerView($id)
    {
        $model  = RetailPartnerReportPreference::find()->where(['id' => $id])->one();
        $columns  =  RetailPartnerReportPreference::columnName();

        return $this->render('retail-partner/view', [
            'model'               => $model,
            'columnNames'       => $columns
        ]);
    }
        
    public function actionCreateRetailPartnerReport()
    {
        $model = new RetailPartnerReportPreferenceForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate(['name', 'columns']) && $model->save()) {
            Yii::$app->session->setFlash('success', "Retail Partner Report Preference created successfully.");
            return $this->redirect(['retail-partner']);
        }
        $columns  =  RetailPartnerReportPreference::columnName();        
        return $this->render('retail-partner/create', ['model' => $model, 'columns' => $columns]);
    }
    public function actionRetailPartnerUpdate($id)
    {
        $columns = RetailPartnerReportPreference::columnName();
        $reportPreference = RetailPartnerReportPreference::findOne($id);
        $reportPreferenceForm = new RetailPartnerReportPreferenceForm();
        $reportPreferenceForm->setReportPreference($reportPreference);
        if ($reportPreferenceForm->load(Yii::$app->request->post())) {
            if ($reportPreferenceForm->save()) {
                Yii::$app->session->setFlash('success', "Retail Partner Report Preference updated successfully.");
                return $this->redirect(['retail-partner']);
            }
        }

        return $this->render('retail-partner/update', [
            'model'  => $reportPreferenceForm,
            'reportPreference' => $reportPreference,
            'columns' => $columns
        ]);
    }


    public function actionExportRetailPartner()
    {
        $preference = RetailPartnerReportPreference::findOne(Yii::$app->request->get('id'));
        $columnsToBeExported = isset($preference) ? explode(",", $preference->columns) : [];
        $members = BusinessPartner::find()->all();
        $allcoll = RetailPartnerReportPreference::columnName();
        $values = array();
        $key = [];
        foreach($columnsToBeExported as $col){
            $key[] = $allcoll[$col];
        }

        foreach ($members as $member) {
            $temp = [];
            foreach($columnsToBeExported as $col){
                if($allcoll[$col]=='Retail Partner Id'){    
                    $temp[] = $member->publicId;
                }elseif($allcoll[$col]=='Business Name'){    
                    $temp[] = $member->name;
                }elseif($allcoll[$col]=='Lead Email'){    
                    $temp[] = $member->email;
                }elseif($allcoll[$col]=='Office Phone Number'){ 
                    /*$memberData = ($member->officePhoneNumber)[0]??'';
                    if($memberData!= '' && $memberData ==0){
                        $temp[] ="'".$member->officePhoneNumber;
                    } else{
                        $temp[] =$member->officePhoneNumber;
                    }*/
                   //substr($str, 0, 7) . ' ' . substr($str, 7);
                   $temp[] = substr_replace($member->officePhoneNumber, ' ',5,0);
                   



                }elseif($allcoll[$col]=='Business Name'){    
                    $temp[] = $member->name;
                }elseif($allcoll[$col]=='Number of Copies of The SubPostmaster'){    
                    $temp[] = $member->magazineCount;
                }elseif($allcoll[$col]=='Contract start date'){    
                    $temp[] = $member->contractStartDate;
                }elseif($allcoll[$col]=='Address 1'){    
                    $temp[] = $member->address->addressLine1;
                }elseif($allcoll[$col]=='Address 2'){    
                    $temp[] = $member->address->addressLine2;
                }elseif($allcoll[$col]=='Address 3'){    
                    $temp[] = $member->address->addressLine3;
                }elseif($allcoll[$col]=='Town'){  
                    $temp[] = $member->address->town;
                }elseif($allcoll[$col]=='County'){    
                    $temp[] = $member->address->county;
                }elseif($allcoll[$col]=='PostCode'){    
                    $temp[] = $member->address->postcode;
                }elseif($allcoll[$col]=='Country'){    
                    $temp[] = $member->address->country;
                }elseif($allcoll[$col]=='Categories'){                      
                    $data = implode(" / ",array_map(function($data){
                        return $data->businessPartnerType->name;
                    },$member->businessPartnerBusinessPartnerTypes))??'N/A';
                    $temp[] = $data;
                }else{
                    $temp[] = $col;
                }
                
            }
            $values[] = $temp;
            
        }
        foreach($values as $collection){
            $newArrs = array_map(function($val){
                    if(is_array($val)){
                        if(empty($val)){
                            return 'N/A';
                        }elseif(count($val) == 1){
                            return !empty($val[0]) ? $val[0] : 'N/A';
                        }else{
                            $newKey = ['child' => $val, 'count' => count($val)];
                            return $newKey ?? "N/A";
                        }
                    }else{
                        return $val ?? '-';
                    }
                },$collection);
                $report = array_column($newArrs, 'count');
                if(!empty($report)){
                    $maxVal = max($report);
                    $reportArr = [];
                    for ($x = 1; $x <= $maxVal; $x++) {
                        $dplk = array_map(function($currentArr) use($x){
                            if(is_array($currentArr)){
                                return $currentArr['child'][($x-1)] ?? "N/A";
                            }else{
                                return $currentArr;
                            }
                        },$newArrs);
                        $duplicate[] = $dplk;
                    }
                }else{
                    $duplicate[] = $newArrs;
                }
        }
                   
         $results = array_map(function ($duplicate) use ($key) {
            return array_combine(array_values($key), $duplicate);
        }, $duplicate);
        
        // $results = array_intersect_key( $results , array_unique( array_map('serialize' , $results)));
        
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results,
        ]);

        // echo "<pre>";    
        // print_r($dataProvider);exit;


        $exporter = new CsvGrid([
            'dataProvider' => $dataProvider,  'csvFileConfig' => [
                'enclosure' => '',
            ],   'columns' => [

         /*   not working [
             'attribute' => 'Office Phone Number',
             'format' => 'text',
         ],*/
     ],
        ]);
        return $exporter->export()->send('Retail-partner-Report-' . date('Y-m-d-G-i-s') . '.csv');
    }

    public function actionRetailPartnerDelete($id)
    {
        $model = RetailPartnerReportPreference::findOne($id);
        $model->delete();
        return $this->redirect(['retail-partner']);
    }



    public function actionRetailPartnerContacts()
    {
        $searchModel = new RetailPartnerContactsPreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('retail-partner-contacts/index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionRetailPartnerContactsView($id)
    {
        $model  = RetailPartnerContactsPreference::find()->where(['id' => $id])->one();
        $columns  =  RetailPartnerContactsPreference::columnName();

        return $this->render('retail-partner-contacts/view', [
            'model'               => $model,
            'columnNames'       => $columns
        ]);
    }
    public function actionCreateRetailPartnerContacts()
    {
        $model = new RetailPartnerContactsPreferenceForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate(['name', 'columns']) && $model->save()) {
            Yii::$app->session->setFlash('success', "Retail Partner Contacts Preference created successfully.");
            return $this->redirect(['retail-partner-contacts']);
        }
        $columns  =  RetailPartnerContactsPreference::columnName();        
        return $this->render('retail-partner-contacts/create', ['model' => $model, 'columns' => $columns]);
    }

    public function actionRetailPartnerContactsUpdate($id)
    {
        $columns = RetailPartnerContactsPreference::columnName();
        $reportPreference = RetailPartnerContactsPreference::findOne($id);
        $reportPreferenceForm = new RetailPartnerContactsPreferenceForm();
        $reportPreferenceForm->setReportPreference($reportPreference);
        if ($reportPreferenceForm->load(Yii::$app->request->post())) {
            if ($reportPreferenceForm->save()) {
                Yii::$app->session->setFlash('success', "Retail Partner Contacts Report Preference updated successfully.");
                return $this->redirect(['retail-partner-contacts']);
            }
        }

        return $this->render('retail-partner-contacts/update', [
            'model'  => $reportPreferenceForm,
            'reportPreference' => $reportPreference,
            'columns' => $columns
        ]);
    }
    public function actionExportRetailPartnerContacts()
    {
        $preference = RetailPartnerContactsPreference::findOne(Yii::$app->request->get('id'));
        $columnsToBeExported = isset($preference) ? explode(",", $preference->columns) : [];
        $members = BusinessPartnerContact::find()->all();
        $allcoll = RetailPartnerContactsPreference::columnName();
        $values = array();
        $key = [];
        foreach($columnsToBeExported as $col){
            $key[] = $allcoll[$col];
        }

        foreach ($members as $contact) {
            $temp = [];
            foreach($columnsToBeExported as $col){
                if($allcoll[$col]=='Retail Partner Contact Id'){    
                    $temp[] = $contact->publicId;
                }elseif($allcoll[$col]=='Retail Partner Business Name'){    
                    $temp[] = $contact->businessPartner->name;
                }elseif($allcoll[$col]=='Primary Contact Yes/No'){    
                    $temp[] = ($contact->id == $contact->businessPartner->primaryContactId) ? 'Yes' : 'No';
                }elseif($allcoll[$col]=='Title'){    
                    $temp[] = $contact->title;
                }elseif($allcoll[$col]=='Forename'){    
                    $temp[] = $contact->forename;
                }elseif($allcoll[$col]=='Surname'){    
                    $temp[] = $contact->surname;
                }elseif($allcoll[$col]=='Job Title'){    
                    $temp[] = $contact->jobTitle;
                }elseif($allcoll[$col]=='Name for use in correspondence'){    
                    $temp[] = $contact->correspondenceName;
                }elseif($allcoll[$col]=='Mobile telephone number'){  
                    $memberData = ($contact->phoneNumber)[0]??'';
                   /* if($memberData!= '' && $memberData ==0){
                        $temp[] ="'".$contact->phoneNumber;
                    } else{
                        $temp[] =$contact->phoneNumber;
                    }*/
                    $temp[] = substr_replace($contact->phoneNumber, ' ',5,0);
                   //$temp[] = chunk_split($contact->phoneNumber, 5, ' ');
                    //$temp[] = $contact->phoneNumber;
                }elseif($allcoll[$col]=='Email Address'){    
                    $temp[] = $contact->email;                
                }else{
                    $temp[] = $col;
                }
                
            }
            $values[] = $temp;
            
        }
        foreach($values as $collection){
            $newArrs = array_map(function($val){
                    if(is_array($val)){
                        if(empty($val)){
                            return 'N/A';
                        }elseif(count($val) == 1){
                            return !empty($val[0]) ? $val[0] : 'N/A';
                        }else{
                            $newKey = ['child' => $val, 'count' => count($val)];
                            return $newKey ?? "N/A";
                        }
                    }else{
                        return $val ?? '-';
                    }
                },$collection);
                $report = array_column($newArrs, 'count');
                if(!empty($report)){
                    $maxVal = max($report);
                    $reportArr = [];
                    for ($x = 1; $x <= $maxVal; $x++) {
                        $dplk = array_map(function($currentArr) use($x){
                            if(is_array($currentArr)){
                                return $currentArr['child'][($x-1)] ?? "N/A";
                            }else{
                                return $currentArr;
                            }
                        },$newArrs);
                        $duplicate[] = $dplk;
                    }
                }else{
                    $duplicate[] = $newArrs;
                }
        }
                   
         $results = array_map(function ($duplicate) use ($key) {
            return array_combine(array_values($key), $duplicate);
        }, $duplicate);
        
        // $results = array_intersect_key( $results , array_unique( array_map('serialize' , $results)));
        
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results,
        ]);

        // echo "<pre>";    
        // print_r($dataProvider);exit;


        $exporter = new CsvGrid([
            'dataProvider' => $dataProvider,  'csvFileConfig' => [
                'enclosure' => '',
            ],
        ]);
        return $exporter->export()->send('Retail-partner-Contacts-' . date('Y-m-d-G-i-s') . '.csv');
    }

    public function actionRetailPartnerContactsDelete($id)
    {
        $model = RetailPartnerContactsPreference::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', "Deleted successfully.");
        return $this->redirect(['retail-partner-contacts']);
    }



    // report for retail partner contact

    public function actionRetailPartnerContact()
    {
        $searchModel = new RetailPartnerContactPreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('retail-partner-contact/index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionRetailPartnerContactView($id)
    {
        $model  = RetailPartnerContactPreference::find()->where(['id' => $id])->one();
        $columns  =  RetailPartnerContactPreference::columnName();

        return $this->render('retail-partner-contact/view', [
            'model'               => $model,
            'columnNames'       => $columns
        ]);
    }
    public function actionCreateRetailPartnerContact()
    {
        $model = new RetailPartnerContactPreferenceForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate(['name', 'columns'])) {           
            if(!empty($_POST['SearchForm']['daterange'])){
             $model->date_from = date('Y-m-d',strtotime(explode(' - ',$_POST['SearchForm']['daterange'])[0]));
             $model->date_to = date('Y-m-d',strtotime(explode(' - ',$_POST['SearchForm']['daterange'])[1]));   
            }
            if($model->save()){
                Yii::$app->session->setFlash('success', "Retail Partner Contact Preference created successfully.");
                return $this->redirect(['retail-partner-contact']);
            }
        }
        $columns  =  RetailPartnerContactPreference::columnName();        
        return $this->render('retail-partner-contact/create', ['model' => $model, 'columns' => $columns]);
    }

    public function actionRetailPartnerContactUpdate($id)
    {
        $columns = RetailPartnerContactPreference::columnName();
        $reportPreference = RetailPartnerContactPreference::findOne($id);
        $reportPreferenceForm = new RetailPartnerContactPreferenceForm();
        $reportPreferenceForm->setReportPreference($reportPreference);
        if ($reportPreferenceForm->load(Yii::$app->request->post())) {
            if(!empty($_POST['SearchForm']['daterange'])){
                $reportPreferenceForm->date_from = date('Y-m-d',strtotime(explode(' - ',$_POST['SearchForm']['daterange'])[0]));
                $reportPreferenceForm->date_to = date('Y-m-d',strtotime(explode(' - ',$_POST['SearchForm']['daterange'])[1]));   
               }
            if ($reportPreferenceForm->save()) {
                Yii::$app->session->setFlash('success', "Retail Partner Contact Report Preference updated successfully.");
                return $this->redirect(['retail-partner-contact']);
            }
        }

        return $this->render('retail-partner-contact/update', [
            'model'  => $reportPreferenceForm,
            'reportPreference' => $reportPreference,
            'columns' => $columns
        ]);
    }
    public function actionExportRetailPartnerContact()
    {
        $preference = RetailPartnerContactPreference::findOne(Yii::$app->request->get('id'));
        $columnsToBeExported = isset($preference) ? explode(",", $preference->columns) : [];

        $clicks = BusinessPartnerClicks::find()->where(['clickType' => 'contact']);
        if(!empty($preference->date_from)){
            $clicks->andWhere(['between', 'createdAt', $preference->date_from,$preference->date_to]);
            // $clicks->where(['>=', 'date_from', $preference->date_from]);
            // $clicks->andWhere(['<=', 'date_to', $preference->date_to]);
        }
        $allclicks = $clicks->all();
        // $allclicks = $clicks->createCommand()->rawSql;

        // echo "<pre>";
        // print_r($allclicks);exit;

        $allcoll = RetailPartnerContactPreference::columnName();
        $values = array();
        $key = [];
        foreach($columnsToBeExported as $col){
            $key[] = $allcoll[$col];
        }

        foreach ($allclicks as $contact) {
            if(!empty($contact->user->userTypeId)){
                if($contact->user->userTypeId == 3){
                    $member = Member::findOne(['userId' => $contact->userId]);                
                }else{
                    $business_partner = BusinessPartner::findOne(['userId' => $contact->userId]);                
                }
            }     

            $temp = [];
            foreach($columnsToBeExported as $col){
                if($allcoll[$col]=='Retail partner name'){    
                    $temp[] = $contact->businessPartner->name;
                }elseif($allcoll[$col]=='Date of contact enquiry'){    
                    $temp[] = date('d/m/Y',strtotime($contact->createdAt));
                }elseif($allcoll[$col]=='Member name'){    
                    if(!empty($member)){                            
                        $temp[] = $member->fullName();
                    }elseif(!empty($business_partner)){
                        $temp[] = $business_partner->name;
                    }else{
                        $temp[] = "N/A";    
                    }                    
                }elseif($allcoll[$col]=='Post Office name'){    
                    if(!empty($member)){    
                        $tmp = [];                        
                        foreach($member->branches as $po){
                            $tmp[] = $po->officeName;                            
                        }
                        $temp[] = implode(' & ',$tmp);
                    }else{
                        $temp[] = "N/A";    
                    }
                }elseif($allcoll[$col]=='Region'){    
                    if(!empty($member)){    
                        $tmp = [];                        
                        foreach($member->branches as $po){
                            $tmp[] = $po->region->name;                            
                        }
                        $temp[] = implode(' & ',$tmp);
                    }else{
                        $temp[] = "N/A";    
                    }
                }else{
                    $temp[] = $col;
                }
                
            }
            $values[] = $temp;            
        }
        $duplicate = [];
        foreach($values as $collection){
            $newArrs = array_map(function($val){
                    if(is_array($val)){
                        if(empty($val)){
                            return 'N/A';
                        }elseif(count($val) == 1){
                            return !empty($val[0]) ? $val[0] : 'N/A';
                        }else{
                            $newKey = ['child' => $val, 'count' => count($val)];
                            return $newKey ?? "N/A";
                        }
                    }else{
                        return $val ?? '-';
                    }
                },$collection);
                $report = array_column($newArrs, 'count');
                if(!empty($report)){
                    $maxVal = max($report);
                    $reportArr = [];
                    for ($x = 1; $x <= $maxVal; $x++) {
                        $dplk = array_map(function($currentArr) use($x){
                            if(is_array($currentArr)){
                                return $currentArr['child'][($x-1)] ?? "N/A";
                            }else{
                                return $currentArr;
                            }
                        },$newArrs);
                        $duplicate[] = $dplk;
                    }
                }else{
                    $duplicate[] = $newArrs;
                }
        }
                   
         $results = array_map(function ($duplicate) use ($key) {
            return array_combine(array_values($key), $duplicate);
        }, $duplicate);
        
        // $results = array_intersect_key( $results , array_unique( array_map('serialize' , $results)));
        
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results,
        ]);

        // echo "<pre>";    
        // print_r($dataProvider);exit;


        $exporter = new CsvGrid([
            'dataProvider' => $dataProvider,  'csvFileConfig' => [
                'enclosure' => '',
            ],
        ]);
        return $exporter->export()->send('Retail-partner-Contact-' . date('Y-m-d-G-i-s') . '.csv');
    }

    public function actionRetailPartnerContactDelete($id)
    {
        $model = RetailPartnerContactPreference::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', "Deleted successfully.");
        return $this->redirect(['retail-partner-contact']);
    }
}
