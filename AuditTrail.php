<?php


/**
 * This is the model class for table "audit_trail".
 */
class AuditTrail extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className Active record class name.
     *
     * @return AuditTrail the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Table name.
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'audit_trail';
    }

    /**
     * Get DB connection.
     *
     * @return CDbConnection|mixed
     */
    public function getDbConnection()
    {
        return Yii::app()->dbAuditTrail;
    }

    /**
     * Rules.
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('action, model, stamp, model_id', 'required'),
            array('action', 'length', 'max' => 255),
            array('model', 'length', 'max' => 255),
            array('field', 'length', 'max' => 255),
            array('model_id', 'length', 'max' => 255),
            array('user_id', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, new_value, old_value, action, model, field, stamp, user_id, model_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Relation.
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * Labels for attributes.
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'old_value' => 'Старое значение',
            'new_value' => 'Новое значение',
            'action' => 'Действие',
            'model' => 'Модель',
            'field' => 'Поле',
            'stamp' => 'Время',
            'user_id' => 'ID пользователя',
            'model_id' => 'ID модели',
        );
    }

    /**
     * Get parent model.
     *
     * @return mixed
     */
    public function getParent()
    {
        $model_name = $this->model;
        return $model_name::model();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('old_value', $this->old_value, true);
        $criteria->compare('new_value', $this->new_value, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('model', $this->model);
        $criteria->compare('field', $this->field, true);
        $criteria->compare('stamp', $this->stamp, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('model_id', $this->model_id);

        return new CActiveDataProvider(
            $this,
            array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => Yii::app()->params['defaultPageSize'],
                ),
            )
        );
    }

    /**
     * Behaviors.
     *
     * @return array
     */
    public function behaviors()
    {
        return array(/*	'ESaveGreedViewState' => array(
				'class' => 'common.modules.YOnixCommon.behaviors.ESaveGridViewState.ESaveGridViewState',
			),*/
        );
    }
}