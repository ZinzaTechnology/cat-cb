<?php

use \Orm\Model;
class Model_Peoples extends Model{
    protected static $_table_name = 'peoples';
    protected static $_primary_key = array('PeopleId');
    protected static $_properties = array(
        'PeopleId', 'PersonUrl', 'FacePhoto', 'Overviews_PrimaryRole', 'Overviews_Investments', 'Overviews_Born', 'Overviews_Gender', 'Overviews_Location',
        'Overviews_Website', 'Overviews_Social_Facebook', 'Overviews_Social_Twitter', 'Overviews_Social_Linkedin', 'Details_Aliases', 'Detail_Text',
        'Jobs_Current', 'Jobs_Past', 'BoardAdvisorRoles', 'Educations', 'created_at', 'last_updated_at'
    );

    public function checkExist($peopleUrl){
        $cp = $this::query()->where('PersonUrl', $peopleUrl)->get_one();
        if($cp) return $cp->PeopleId;
        return 0;
    }

    public function update(){
        $dateNow = date('Y-m-d H:i:s');
        $peopleId = 0;//$this->checkExist($this->PersonUrl);
        if($peopleId > 0){
            $data = array(
                'FacePhoto' => $this->FacePhoto,
                'Overviews_PrimaryRole' => $this->Overviews_PrimaryRole,
                'Overviews_Investments' => $this->Overviews_Investments,
                'Overviews_Born' => $this->Overviews_Born,
                'Overviews_Gender' => $this->Overviews_Gender,
                'Overviews_Location' => $this->Overviews_Location,
                'Overviews_Website' => $this->Overviews_Website,
                'Overviews_Social_Facebook' => $this->Overviews_Social_Facebook,
                'Overviews_Social_Twitter' => $this->Overviews_Social_Twitter,
                'Overviews_Social_Linkedin' => $this->Overviews_Social_Linkedin,
                'Details_Aliases' => $this->Details_Aliases,
                'Detail_Text' => $this->Detail_Text,
                'Jobs_Current' => $this->Jobs_Current,
                'Jobs_Past' => $this->Jobs_Past,
                'BoardAdvisorRoles' => $this->BoardAdvisorRoles,
                'Educations' => $this->Educations,
                'last_updated_at' => $dateNow
            );
            DB::update('peoples')->set($data)->where('PeopleId', $peopleId)->execute();
        }
        else{
            $this->created_at = $dateNow;
            $this->save();
        }
    }
}