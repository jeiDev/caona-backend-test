<?php

namespace app\models;

use Yii;
use app\models\Clients;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $sexo
 * @property int|null $client_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class Profiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'phone', 'client_id'], 'required'],
            [['first_name', 'last_name', 'phone'], 'string'],
            [['client_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['sexo'], 'string', 'max' => 1],
            ['sexo', 'in', 'range' => ['M', 'F'], 'message' => 'Please select a valid option for gender.'],
            ['client_id', 'exist', 'targetClass' => Clients::className(), 'targetAttribute' => 'id'],
            ['client_id', 'validateIsCreate'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'sexo' => 'Sexo',
            'client_id' => 'Client ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function validateIsCreate($attribute, $params)
    {
        if (Yii::$app->request->isPost) {
            $clienteId = $this->$attribute;
    
            $pedidoExistente = Profiles::find()
                ->where(['client_id' => $clienteId])
                ->exists();
    
            if ($pedidoExistente) {
                $this->addError($attribute, 'There is already a relationship between this address and the client.');
            }
        }
    }
}
