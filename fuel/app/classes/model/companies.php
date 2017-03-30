<?php

use \Orm\Model;
class Model_Companies extends Model{
    protected static $_table_name = 'companies';
    protected static $_primary_key = array('CompanyId');
    protected static $_properties = array(
        'CompanyId', 'CompanyUrl', 'Logo', 'Overview_Acquistions', 'Overview_TotalEqyityFunding', 'Overview_IPOStock', 'Overview_Headquarters', 'Overview_Description',
        'Overview_Founders', 'Overview_Categories', 'Overview_Website', 'Overview_Social_Facebook', 'Overview_Social_Twitter', 'Overview_Social_Linkedin',
        'Details_Founded', 'Details_Contact', 'Details_Employees', 'Details_Text', 'FundingRounds', 'FundingRounds_Details', 'CurrentTeams', 'Products', 'SubOrganizations',
        'Competirors', 'Partners', 'OfficesLocations', 'PastTeams', 'Awards', 'created_at', 'last_updated_at'
    );

    public function checkExist($companyUrl){
        $cp = $this::query()->where('CompanyUrl', $companyUrl)->get_one();
        if($cp) return $cp->CompanyId;
        return 0;
    }

    public function update(){
        $dateNow = date('Y-m-d H:i:s');
        $companyId = 0;//$this->checkExist($this->CompanyUrl);
        if($companyId > 0){
            $data = array(
                'Logo' => $this->Logo,
                'Overview_Acquistions' => $this->Overview_Acquistions,
                'Overview_TotalEqyityFunding' => $this->Overview_TotalEqyityFunding,
                'Overview_IPOStock' => $this->Overview_IPOStock,
                'Overview_Headquarters' => $this->Overview_Headquarters,
                'Overview_Description' => $this->Overview_Description,
                'Overview_Founders' => $this->Overview_Founders,
                'Overview_Categories' => $this->Overview_Categories,
                'Overview_Website' => $this->Overview_Website,
                'Overview_Social_Facebook' => $this->Overview_Social_Facebook,
                'Overview_Social_Twitter' => $this->Overview_Social_Twitter,
                'Overview_Social_Linkedin' => $this->Overview_Social_Linkedin,
                'Details_Founded' => $this->Details_Founded,
                'Details_Contact' => $this->Details_Contact,
                'Details_Employees' => $this->Details_Employees,
                'Details_Text' => $this->Details_Text,
                'FundingRounds' => $this->FundingRounds,
                'FundingRounds_Details' => $this->FundingRounds_Details,
                'CurrentTeams' => $this->CurrentTeams,
                'Products' => $this->Products,
                'SubOrganizations' => $this->SubOrganizations,
                'Competirors' => $this->Competirors,
                'Partners' => $this->Partners,
                'OfficesLocations' => $this->OfficesLocations,
                'PastTeams' => $this->PastTeams,
                'Awards' => $this->Awards,
                'last_updated_at' => $dateNow
            );
            DB::update('companies')->set($data)->where('CompanyId', $companyId)->execute();
        }
        else{
            $this->created_at = $dateNow;
            $this->save();
        }
    }
}