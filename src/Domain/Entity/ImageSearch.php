<?php

namespace Domain\Entity;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ImageSearch represents the model behind the search form about `common\modules\ImageManager\Domain\Entity\Image`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_model', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['path', 'img_name', 'file_type', 'filename', 'name_model', 'img_name_thumb', 'category'], 'safe'],
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
        $query = Image::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_model' => $this->id_model,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'img_name', $this->img_name])
            ->andFilterWhere(['like', 'file_type', $this->file_type])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'name_model', $this->name_model])
            ->andFilterWhere(['like', 'img_name_thumb', $this->img_name_thumb])
            ->andFilterWhere(['like', 'category', $this->category]);

        return $dataProvider;
    }
}
