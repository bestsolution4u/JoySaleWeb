<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sitesettings;

/**
 * SitesettingsSearch represents the model behind the search form of `common\models\Sitesettings`.
 */
class SitesettingsSearch extends Sitesettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'smtpEnable', 'smtpSSL', 'urgentPrice', 'searchDistance', 'commission_status', 'facebookshare', 'bannerstatus', 'promotionStatus', 'product_autoapprove', 'bannervideoStatus'], 'integer'],
            [['smtpEmail', 'smtpPassword', 'smtpPort', 'smtpHost', 'signup_active', 'givingaway', 'socialLoginDetails', 'logo', 'logoDarkVersion', 'sitename', 'metaData', 'default_userimage','default_productimage', 'favicon', 'currency_priority', 'category_priority', 'promotionCurrency', 'searchType', 'searchList', 'sitepaymentmodes', 'paypal_settings', 'braintree_settings', 'braintree_merchant_ids', 'api_settings', 'footer_settings', 'tracking_code', 'googleapikey', 'staticMapApiKey', 'account_sid', 'auth_token', 'sms_number','pricerange', 'fb_appid', 'fb_secret', 'androidkey', 'bannervideo', 'bannervideoposter', 'bannerText'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Sitesettings::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'smtpEnable' => $this->smtpEnable,
            'smtpSSL' => $this->smtpSSL,
            'urgentPrice' => $this->urgentPrice,
            'searchDistance' => $this->searchDistance,
            'commission_status' => $this->commission_status,
            'facebookshare' => $this->facebookshare,
            'bannerstatus' => $this->bannerstatus,
            'promotionStatus' => $this->promotionStatus,
            'product_autoapprove' => $this->product_autoapprove,
            'bannervideoStatus' => $this->bannervideoStatus,
        ]);

        $query->andFilterWhere(['like', 'smtpEmail', $this->smtpEmail])
            ->andFilterWhere(['like', 'smtpPassword', $this->smtpPassword])
            ->andFilterWhere(['like', 'smtpPort', $this->smtpPort])
            ->andFilterWhere(['like', 'smtpHost', $this->smtpHost])
            ->andFilterWhere(['like', 'signup_active', $this->signup_active])
            ->andFilterWhere(['like', 'givingaway', $this->givingaway])
            ->andFilterWhere(['like', 'socialLoginDetails', $this->socialLoginDetails])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'logoDarkVersion', $this->logoDarkVersion])
            ->andFilterWhere(['like', 'sitename', $this->sitename])
            ->andFilterWhere(['like', 'metaData', $this->metaData])
            ->andFilterWhere(['like', 'default_userimage', $this->default_userimage])
            ->andFilterWhere(['like', 'default_productimage', $this->default_productimage])
            ->andFilterWhere(['like', 'favicon', $this->favicon])
            ->andFilterWhere(['like', 'currency_priority', $this->currency_priority])
            ->andFilterWhere(['like', 'category_priority', $this->category_priority])
            ->andFilterWhere(['like', 'promotionCurrency', $this->promotionCurrency])
            ->andFilterWhere(['like', 'searchType', $this->searchType])
            ->andFilterWhere(['like', 'searchList', $this->searchList])
            ->andFilterWhere(['like', 'sitepaymentmodes', $this->sitepaymentmodes])
            ->andFilterWhere(['like', 'paypal_settings', $this->paypal_settings])
            ->andFilterWhere(['like', 'braintree_settings', $this->braintree_settings])
            ->andFilterWhere(['like', 'braintree_merchant_ids', $this->braintree_merchant_ids])
            ->andFilterWhere(['like', 'api_settings', $this->api_settings])
            ->andFilterWhere(['like', 'footer_settings', $this->footer_settings])
            ->andFilterWhere(['like', 'tracking_code', $this->tracking_code])
            ->andFilterWhere(['like', 'googleapikey', $this->googleapikey])
            ->andFilterWhere(['like', 'staticMapApiKey', $this->staticMapApiKey])
            ->andFilterWhere(['like', 'account_sid', $this->account_sid])
            ->andFilterWhere(['like', 'auth_token', $this->auth_token])
            ->andFilterWhere(['like', 'sms_number', $this->sms_number])
            ->andFilterWhere(['like', 'fb_appid', $this->fb_appid])
            ->andFilterWhere(['like', 'fb_secret', $this->fb_secret])
            ->andFilterWhere(['like', 'androidkey', $this->androidkey])
            ->andFilterWhere(['like', 'bannervideo', $this->bannervideo])
            ->andFilterWhere(['like', 'bannervideoposter', $this->bannervideoposter])
            ->andFilterWhere(['like', 'bannerText', $this->bannerText]);

        return $dataProvider;
    }
}
