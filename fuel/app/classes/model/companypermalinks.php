<?php

use \Orm\Model;
class Model_Companypermalinks extends Model{
    protected static $_table_name = 'companypermalinks';
    protected static $_primary_key = array('CompanyId');
    protected static $_properties = array('CompanyId', 'Permalink', 'created_at', 'last_updated_at');

    public function checkExist($permalink){
        $cp = $this::query()->where('Permalink', $permalink)->get_one();
        if($cp) return $cp->CompanyId;
        return 0;
    }

    public function update(){
        $dateNow = date('Y-m-d H:i:s');
        $companyId = $this->checkExist($this->Permalink);
        if($companyId > 0){
            DB::update('companypermalinks')->set(array('last_updated_at' => $dateNow))->where('CompanyId', $companyId)->execute();
        }
        else{
            $this->created_at = $dateNow;
            $this->save();
        }
    }

    public function getRandom($limit){
        return DB::query('SELECT * FROM companypermalinks WHERE CompanyId NOT IN(SELECT CompanyId FROM companies) ORDER BY RAND() LIMIT '.$limit)->as_object('Model_Companypermalinks')->execute()->as_array();

    }
    
    public function getCountRow(){
        $counts = DB::query('SELECT COUNT(1) AS count1 FROM companypermalinks')->execute()->as_array();
        if(!empty($counts)) return $counts[0]['count1'];
        return 0;
    }
}