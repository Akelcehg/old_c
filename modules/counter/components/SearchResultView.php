<?php
namespace app\modules\counter\components;



use app\models\Search;
use Yii;
use yii\helpers\Html;

class SearchResultView extends \yii\base\Widget
{

    public $limit=12;



    public function run() {

        $text=Yii::$app->request->get("search_string",false);


        if ($text) {
            $search = Search::find()
                ->where(['like', 'search_string', $text])
                ->limit($this->limit)
                ->all();
        }else{
            $search=[];
        }

        $html="";
        if($search) {
            foreach($search as $oneSearch)
            {
                if($oneSearch->type=="corrector") {
                    $link=Html::a($oneSearch->search_string,['/prom/correctors/view/','id'=>$oneSearch->counter_id]);
                }else{
                    $link=Html::a($oneSearch->search_string,['/counter/counter/view/','id'=>$oneSearch->counter_id]);
                }

                $html.=Html::tag('p',$link);
            }
            return Html::tag('div',$html);
        }
    }
}