<?php

use \Orm\Model;
class Model_Peoplepermalinks extends Model{
    protected static $_table_name = 'peoplepermalinks';
    protected static $_primary_key = array('PeopleId');
    protected static $_properties = array('PeopleId', 'Permalink', 'created_at', 'last_updated_at');

    public function checkExist($permalink){
        $cp = $this::query()->where('Permalink', $permalink)->get_one();
        if($cp) return $cp->PeopleId;
        return 0;
    }

    public function update(){
        $dateNow = date('Y-m-d H:i:s');
        $peopleId = $this->checkExist($this->Permalink);
        if($peopleId > 0){
            DB::update('peoplepermalinks')->set(array('last_updated_at' => $dateNow))->where('PeopleId', $peopleId)->execute();
        }
        else{
            $this->created_at = $dateNow;
            $this->save();
        }
    }
    
    public function getRandom($limit){
        return DB::query('SELECT * FROM peoplepermalinks WHERE PeopleId NOT IN(SELECT PeopleId FROM peoples) ORDER BY RAND() LIMIT '.$limit)->as_object('Model_Peoplepermalinks')->execute()->as_array();
    }
    
    public function getCountRow(){
        $counts = DB::query('SELECT COUNT(1) AS count1 FROM peoplepermalinks')->execute()->as_array();
        if(!empty($counts)) return $counts[0]['count1'];
        return 0;
    }
}