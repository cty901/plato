<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "borrow_return_books".
 *
 * @property int $id
 * @property int $reader_id 名称
 * @property string $card_number 卡号
 * @property string $bar_code 条码号
 * @property int $operation 借还操作:1借，0还
 * @property int $library_id 图书馆ID
 * @property int $user_id 操作员ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 状态
 */
class BorrowReturnBooks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'borrow_return_books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reader_id', 'card_number', 'bar_code', 'operation', 'library_id'], 'required'],
            [['reader_id', 'operation', 'library_id', 'user_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['card_number'], 'string', 'max' => 64],
            [['bar_code'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reader_id' => '名称',
            'card_number' => '卡号',
            'bar_code' => '条码号',
            'operation' => '借还操作:1借，0还',
            'library_id' => '图书馆ID',
            'user_id' => '操作员ID',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态',
        ];
    }
    

    public static function getReaderInfoAjax($ardnumber_or_barcode)
    {
        
// 姓名	柳秀芳	卡号	4024007145093018	证件状态	1	最大可借数(本)	8
// 读者类型	2	性别	1	有效期限	1767139200	当前借阅数(本)	0
// 押金(元)	100	最大欠款额度(元)	3	欠费金额(元)	0	
        
        $reader = Reader::findOne(['card_number' => $ardnumber_or_barcode]);
        $reader_type = ReaderType::findOne(['id' => $reader->reader_type_id]);

        $reader_info = [
            'card_number' => $reader->card_number,
            'reader_name' => $reader->reader_name,
            'card_status' => Reader::getCardStatusOption($reader->card_status),
            'validity' => date('Y-m-d',$reader->validity),
            'id_card' => $reader->id_card,
            'reader_type_id' => Reader::getReaderTypeOption($reader->reader_type_id),
            'gender' => Reader::getGenderOption($reader->gender),
            'deposit' => $reader->deposit,
            'creditmoney' => $reader->creditmoney,
            'mobile' => $reader->mobile,
            'max_borrowing_number' => $reader_type->max_borrowing_number,
            'max_debt_limit' => $reader_type->max_debt_limit,
        ];

        if(empty($reader))
        {
            return \yii\helpers\Json::encode(['code' => -1]);
        }
        else
        {

            return \yii\helpers\Json::encode(['code' => 0, 'reader_info' => $reader_info]);
        }
        
    }

    public function getTReader()
    {
        return $this->hasOne(Reader::className(), ['id' => 'reader_id' ]);
    }

}
