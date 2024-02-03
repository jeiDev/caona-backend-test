<?php

namespace app\models;

use Yii;
use app\models\Clients;

/**
 * This is the model class for table "addresses".
 *
 * @property int $id
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $country
 * @property int|null $client_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class Addresses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'city', 'state', 'postal_code', 'country', 'client_id'], 'required'],
            [['address', 'city', 'state', 'postal_code', 'country'], 'string'],
            [['client_id'], 'integer'],
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
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
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
    
            $pedidoExistente = Addresses::find()
                ->where(['client_id' => $clienteId])
                ->exists();
    
            if ($pedidoExistente) {
                $this->addError($attribute, 'There is already a relationship between this address and the client.');
            }
        }
    }
}
