<?php

/**
* Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;


/**
* App example task
 *
 * Ruthlessly stolen from the beareded Canadian sexy symbol:
 *
 *		Derek Allard: http://derekallard.com/
 *
 * @package		Fuel
 * @version		1.0
 * @author		Phil Sturgeon
 */

class App
{
	public function appGetListCompany(){
		$cp = new \Model_Companypermalinks();
		$rankBegin = floor($cp->getCountRow() * 1.8);
		$cookieFileName = dirname(__FILE__) . "/cookies.txt";
		$listPermalinks = $this->getListCompany($cookieFileName, $rankBegin);
		if(count($listPermalinks) < 50){
			$this->getCookie($cookieFileName);
			$listPermalinks = $this->getListCompany($cookieFileName, $rankBegin);
		}
		foreach($listPermalinks as $permalink){
			$cp = new \Model_Companypermalinks();
			$cp->Permalink = $permalink;
			$cp->update();
			echo $permalink . PHP_EOL;
		}
	}
	
	public function appGetListPeople(){
		$cp = new \Model_Peoplepermalinks();
		$rankBegin = floor($cp->getCountRow() * 1.8);   
    $cookieFileName = dirname(__FILE__) . "/cookies.txt";
		$listPermalinks = $this->getListPeople($cookieFileName, $rankBegin);
		if(count($listPermalinks) < 50){
			$this->getCookie($cookieFileName);
			$listPermalinks = $this->getListPeople($cookieFileName, $rankBegin);
		}
		foreach($listPermalinks as $permalink){
			$cp = new \Model_Peoplepermalinks();
			$cp->Permalink = $permalink;
			$cp->update();
			echo $permalink . PHP_EOL;
		}
	}
	
	
	public function appGetCompanyDetail()
		{
		$proxiesAgents = $this->getProxiesAgents();
		$countProxy = count($proxiesAgents);
		$proxies = array_keys($proxiesAgents);
		$cp = new \Model_Companypermalinks();
		$listPermalinks = $cp->getRandom(1);
		foreach($listPermalinks as $p) {
			$company = false;
			//$i = 0;
			//$index = $p->CompanyId % $countProxy;
      $index = 0;
			$fp = @fopen(dirname(__FILE__) . "/currentproxy.txt", "r");
			if ($fp){
				$index = fgets($fp);
				fclose($fp);
			}
			else $index = rand(0, $countProxy);
      
      $proxy = $proxies[$index];
				$userAgent = $proxiesAgents[$proxy];
				$company = $this->getCompanyDetail($p->CompanyId, $p->Permalink, $proxy, $userAgent);
				if($company === -1){
					\Log::error('404: ' . $p->CompanyId . ': ' . $p->Permalink);
					break;
				}
				elseif($company) {
					$company->update();
					echo $p->Permalink . PHP_EOL;          
				}
				else{
					\Log::error('Proxy: ' . $proxy . ' - ' . $p->CompanyId . ': ' . $p->Permalink);
				}
        $fp = @fopen(dirname(__FILE__) . "/currentproxy.txt", "w");
  				if($fp){
  					$index++;
  					if($index >= $countProxy) $index = 0;
  					fwrite($fp, $index);
  				}      
			/*while($company === false){
				$i++;
				$proxy = $proxies[$index];
				$userAgent = $proxiesAgents[$proxy];
				$company = $this->getCompanyDetail($p->CompanyId, $p->Permalink, $proxy, $userAgent);
				if($company === -1){
					\Log::error('404: ' . $p->CompanyId . ': ' . $p->Permalink);
					break;
				}
				elseif($company) {
					$company->update();
					echo $p->Permalink . PHP_EOL;
					break;
				}
				else{
					\Log::error('Proxy: ' . $proxy . ' - ' . $p->CompanyId . ': ' . $p->Permalink);
				}
				if($i >= $countProxy) break;
				$index++;
				if($index == $countProxy) $index = 0;
				usleep(20000000);
			}*/
		}
	}
	
	public function appGetPeopleDetail()
		{
		$proxiesAgents = $this->getProxiesAgents();
		$countProxy = count($proxiesAgents);
		$proxies = array_keys($proxiesAgents);
		$cp = new \Model_Peoplepermalinks();
		$listPermalinks = $cp->getRandom(1);
		foreach($listPermalinks as $p) {
			$people = false;
			//$i = 0;
			//$index = $p->PeopleId % $countProxy;
      $fp = @fopen(dirname(__FILE__) . "/currentproxy.txt", "r");
			if ($fp){
				$index = fgets($fp);
				fclose($fp);
			}
			else $index = rand(0, $countProxy);
      
      $proxy = $proxies[$index];
				$userAgent = $proxiesAgents[$proxy];
				$people = $this->getPersonDetail($p->PeopleId, $p->Permalink, $proxy, $userAgent);
				if($people === -1){
					\Log::error('People 404: ' . $p->PeopleId . ': ' . $p->Permalink);
					break;
				}
				elseif ($people) {
					$people->update();
					echo $p->Permalink . PHP_EOL;          
				}
				else{
					\Log::error('People Proxy: ' . $proxy . ' - ' . $p->PeopleId . ': ' . $p->Permalink);
				}
        $fp = @fopen(dirname(__FILE__) . "/currentproxy.txt", "w");
  				if($fp){
  					$index++;
  					if($index >= $countProxy) $index = 0;
  					fwrite($fp, $index);
  				}
			/*while($people === false) {
				$i++;
				$proxy = $proxies[$index];
				$userAgent = $proxiesAgents[$proxy];
				$people = $this->getPersonDetail($p->PeopleId, $p->Permalink, $proxy, $userAgent);
				if($people === -1){
					\Log::error('People 404: ' . $p->PeopleId . ': ' . $p->Permalink);
					break;
				}
				elseif ($people) {
					$people->update();
					echo $p->Permalink . PHP_EOL;
					break;
				}
				else{
					\Log::error('People Proxy: ' . $proxy . ' - ' . $p->PeopleId . ': ' . $p->Permalink);
				}
				if($i >= $countProxy) break;
				$index++;
				if($index == $countProxy) $index = 0;
				usleep(20000000);
			}*/
		}
	}
	
	private function getCompanyDetail($companyId, $permalink, $proxy, $userAgent){
		$companyUrl = 'https://www.crunchbase.com/organization/'.$permalink;
		$html = $this->initCurl($companyUrl, $proxy, $userAgent);
		if($html == '404') return -1;
		if(!empty($html)){
			$retVal = new \Model_Companies();
			$retVal->CompanyId = $companyId;
			$retVal->CompanyUrl = $companyUrl;
			require_once dirname(__FILE__) . "/simple_html_dom.php";
			$html = str_get_html($html);
			$html = $html->find('div.large-10', 0);
			if($html != null) {
				$item = $html->find('img.entity-info-card-primary-image', 0);
				if ($item != null) $retVal->Logo = $item->src;
				
				$section = $html->find('div.definition-list-container', 0);
				if ($section != null) {
					//Overview
					$overviews = array();
					foreach ($section->find('dt') as $dt) {
						$label = $this->replaceHtmlChar(str_replace(':', '', $dt->plaintext));
						$dd = $dt->next_sibling();
						$value = '';
						if ($dd != null) $value = trim($dd->plaintext);
						if (!empty($label) && !empty($value)) $overviews[$label] = $value;
						if($label == 'Social' && $dd != null){
							foreach($dd->find('a') as $a){
								if(strpos($a->href, 'facebook.com') !== false) $retVal->Overview_Social_Facebook = $a->href;
								elseif(strpos($a->href, 'twitter.com') !== false) $retVal->Overview_Social_Twitter = $a->href;
								elseif(strpos($a->href, 'linkedin.com') !== false) $retVal->Overview_Social_Linkedin = $a->href;
							}
						}
					}
					foreach($overviews as $label => $value){
						if($label == 'Acquisitions') $retVal->Overview_Acquistions = $value;
						elseif($label == 'Total Equity Funding') $retVal->Overview_TotalEqyityFunding = $value;
						elseif($label == 'IPO / Stock') $retVal->Overview_IPOStock = $value;
						elseif($label == 'Founders') $retVal->Overview_Founders = $value;
						elseif($label == 'Headquarters') $retVal->Overview_Headquarters = $value;
						elseif($label == 'Categories') $retVal->Overview_Categories = $value;
						elseif($label == 'Description') $retVal->Overview_Description = $value;
						elseif($label == 'Website') $retVal->Overview_Website = $value;
					}
				}
				
				$section = $html->find('div.timeline', 0);
				if ($section != null) {
					//Detail
					$details = array();
					foreach ($section->find('div.definition-list dt') as $dt) {
						$label = $this->replaceHtmlChar(str_replace(':', '', $dt->plaintext));
						$dd = $dt->next_sibling();
						$value = '';
						if ($dd != null) {
							$value = $this->replaceHtmlChar($dd->plaintext);
							if($label == 'Contact'){
								$detailContact = '';
								foreach($dd->find('span.email a') as $a){
									if(strpos($a->plaintext, '@') !== false){
										$detailContact = trim($a->plaintext);
										break;
									}
								}
								$txt = $dd->innertext;
								if(empty($detailContact)) {
									$txt = $dd->innertext;
									$startIndex = strpos($txt, 'data-cfemail=');
									$endIndex = strpos($txt, '>', $startIndex);
									if($startIndex > 0 && $endIndex > $startIndex){
										$cfmail = substr($txt, $startIndex, $endIndex);
										$cfmail = str_replace(array('data-cfemail=', '"'), '', $cfmail);
										$startIndex = strpos($cfmail, '>');
										if($startIndex > 0) {
											$cfmail = trim(substr($cfmail, 0, $startIndex));
											$detailContact = $this->deCFEmail($cfmail);
										}
									}
								}
								$startIndex = strpos($txt, "<span class='phone_number'>");
								$endIndex = strpos($txt, '</a>', $startIndex);
								if((empty($detailContact) && $startIndex >= 0 && $endIndex > $startIndex) || (!empty($detailContact) && $startIndex > 0 && $endIndex > $startIndex)){
									$phone = substr($txt, $startIndex, $endIndex);
									$phone = trim(str_replace(array("<span class='phone_number'>", '</a>'), '', $phone));
									if(!empty($phone)){
										if(!empty($detailContact)) $detailContact .= ' | '.$phone;
										else $detailContact = $phone;
									}
								}
								$retVal->Details_Contact = $detailContact;
							}
						}
						if (!empty($label) && !empty($value)) $details[$label] = $value;
					}
					foreach($details as $label => $value){
						if($label == 'Founded') $retVal->Details_Founded = $value;
						elseif($label == 'Employees') $retVal->Details_Employees = $value;
					}
					$item = $section->find('div.description-ellipsis', 0);
					if ($item != null) {
						$detailText = $this->replaceHtmlChar($item->innertext);
						$retVal->Details_Text = trim(str_replace('<span class="hellips"></span>', '', $detailText));
					}
				}
				
				$section = $html->find('div.funding_rounds', 0);
				if ($section != null) {
					$item = $section->find('h2#funding_rounds', 0);
					if ($item != null) $retVal->FundingRounds = $this->replaceHtmlChar($item->plaintext);
					$fundings = array();
					foreach($section->find('table tbody tr') as $tr){
						$value = '';
						foreach($tr->find('td') as $td){
							$text = $this->replaceHtmlChar($td->plaintext);
							if($text != '—') $value.=$text.'|';
						}
						if(!empty($value)) $fundings[]= substr($value, 0, strlen($value) - 1);
					}
					$retVal->FundingRounds_Details = json_encode($fundings);
				}
				
				//Current Team
				$session = $html->find('div.people', 0);
				if ($session != null) {
					$currentTeam = array();
					foreach ($session->find('div.card-slim ul li div.info-block div.large') as $li) {
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $currentTeam[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
						elseif($item1 != null) $currentTeam[] = $this->replaceHtmlChar($item1->plaintext);
						
					}
					$retVal->CurrentTeams = json_encode($currentTeam);
				}
				
				//Products
				$section = $html->find('div.products', 0);
				if ($section != null) {
					$products = array();
					foreach ($section->find('div.card-content ul li div.info-block div') as $li) {
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $products[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
						elseif($item1 != null) $products[] = $this->replaceHtmlChar($item1->plaintext);
					}
					$retVal->Products = json_encode($products);
				}
				
				//Sub Organizations
				$section = $html->find('div.sub_organizations', 0);
				if($section != null){
					$subOrganizations = array();
					foreach($section->find('div.card-content ul li div.info-block') as $li){
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $subOrganizations[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
						elseif($item1 != null) $subOrganizations[] = $this->replaceHtmlChar($item1->plaintext);
					}
					$retVal->SubOrganizations = json_encode($subOrganizations);
				}
				
				//Competirors
				$section = $html->find('div.competitors', 0);
				if($section != null){
					$competirors = array();
					foreach($section->find('div.card-content ul li div.info-block div ') as $li){
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $competirors[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
					}
					$retVal->Competirors = json_encode($competirors);
				}
				
				//Partners
				$section = $html->find('div.partners', 0);
				if($section != null){
					$partners = array();
					foreach($section->find('div.card-content ul li div.info-block div') as $li){
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $partners[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
						elseif($item1 != null) $partners[] = $this->replaceHtmlChar($item1->plaintext);
					}
					$retVal->Partners = json_encode($partners);
				}
				
				//Offices / Locations
				$section = $html->find('div.addresses', 0);
				if($section != null){
					$officesLocations = array();
					foreach($section->find('div.card-content ul li div.info-block ') as $li){
						$label = '';
						$item = $li->find('h4', 0);
						if($item != null) $label = $this->replaceHtmlChar($item->plaintext);
						$value = '';
						foreach($li->find('p') as $p){
							$text = $this->replaceHtmlChar($p->plaintext);
							if(!empty($text)) $value .=  $text . ' - ';
						}
						if (!empty($label) && !empty($value)){
							$officesLocations[] = $label.' | '.substr($value, 0, strlen($value) - 2);
						}
					}
					$retVal->OfficesLocations = json_encode($officesLocations);
				}
				
				//Past Team
				$section = $html->find('div.past_people', 0);
				if($section != null){
					$pastTeam = array();
					foreach($section->find('div.card-content ul li div.info-block') as $li){
						$item1 = $li->find('h4 a', 0);
						$item2 = $li->find('h5', 0);
						if ($item1 != null && $item2 != null) $pastTeam[] = $this->replaceHtmlChar($item1->plaintext).' | '.$this->replaceHtmlChar($item2->plaintext);
						elseif($item1 != null) $pastTeam[] = $this->replaceHtmlChar($item1->plaintext);
					}
					$retVal->PastTeams = json_encode($pastTeam);
				}
				
				//Awards
				$section = $html->find('div.awards', 0);
				if($section != null){
					$awards = array();
					foreach($section->find('ul li') as $li) $awards[]=$this->replaceHtmlChar($li->plaintext);
					$retVal->Awards = json_encode($awards);
				}
				return $retVal;
			}
		}
		return false;
	}
	
	private function getPersonDetail($peopleId, $peopleSlug, $proxy, $userAgent){
		$peopleUrl = 'https://www.crunchbase.com/person/'.$peopleSlug;
		$html = $this->initCurl($peopleUrl, $proxy, $userAgent);
		if($html == '404') return -1;
		if(!empty($html)){
			$retVal = new \Model_Peoples();
			$retVal->PeopleId = $peopleId;
			$retVal->PersonUrl = $peopleUrl;
			require_once dirname(__FILE__) . "/simple_html_dom.php";
			$html = str_get_html($html);
			$html = $html->find('div.large-10', 0);
			if($html != null) {
				$item = $html->find('img.entity-info-card-primary-image', 0);
				if ($item != null) $retVal->FacePhoto = $item->src;
				
				//Overview
				$session = $html->find('div.definition-list-container', 0);
				if ($session != null) {
					$overviews = array();
					foreach ($session->find('dt') as $dt) {
						$label = $this->replaceHtmlChar(str_replace(':', '', $dt->plaintext));
						$dd = $dt->next_sibling();
						$value = '';
						if ($dd != null) $value = $this->replaceHtmlChar($dd->plaintext);
						if (!empty($label) && !empty($value)) $overviews[$label] = $value;
						if ($label == 'Social' && $dd != null) {
							foreach ($dd->find('a') as $a){
								if(strpos($a->href, 'facebook.com') !== false) $retVal->Overviews_Social_Facebook = $a->href;
								elseif(strpos($a->href, 'twitter.com') !== false) $retVal->Overviews_Social_Twitter = $a->href;
								elseif(strpos($a->href, 'linkedin.com') !== false) $retVal->Overviews_Social_Linkedin = $a->href;
							}
						}
					}
					foreach($overviews as $label => $value){
						if($label == 'Primary Role') $retVal->Overviews_PrimaryRole = $value;
						elseif($label == 'Investments') $retVal->Overviews_Investments = $value;
						elseif($label == 'Born') $retVal->Overviews_Born = $value;
						elseif($label == 'Gender') $retVal->Overviews_Gender = $value;
						elseif($label == 'Location') $retVal->Overviews_Location = $value;
						elseif($label == 'Website') $retVal->Overviews_Website = $value;
					}
				}
				
				//Person Details
				$session = $html->find('div.description', 0);
				if ($session != null) {
					foreach ($session->find('dt') as $dt) {
						$label = $this->replaceHtmlChar(str_replace(':', '', $dt->plaintext));
						$dd = $dt->next_sibling();
						$value = '';
						if ($dd != null) $value = $this->replaceHtmlChar($dd->plaintext);
						if ($label == 'Aliases' && !empty($value)) $retVal->Details_Aliases = $value;
					}
					
					$item = $session->find('div.description-ellipsis', 0);
					if ($item != null){
						$detailText = $this->replaceHtmlChar($item->innertext);
						$retVal->Detail_Text = trim(str_replace('<span class="hellips"></span>', '', $detailText));
					}
				}
				
				//Jobs
				$session = $html->find('div.experiences', 0);
				if ($session != null) {
					$jobs = array();
					foreach ($session->find('div.current_job div.info-block') as $div) {
						$item = $div->find('h4', 0);
						if ($item != null) {
							$value = $this->replaceHtmlChar($item->plaintext);
							foreach ($div->find('h5') as $a) {
								$text = $this->replaceHtmlChar($a->plaintext);
								if (!empty($text)) $value .= ' | ' . $text;
							}
							$jobs[] = $value;
						}
					}
					$retVal->Jobs_Current = json_encode($jobs);
					$jobs = array();
					foreach ($session->find('div.past_job div.info-row') as $div) {
						$value = '';
						foreach($div->find('div.cell') as $td){
							if($td->class != 'cell header' && $td->class != 'cell footer') $value.=$this->replaceHtmlChar($td->plaintext).'|';
						}
						if(!empty($value)) $jobs[]= substr($value, 0, strlen($value) - 1);
					}
					$retVal->Jobs_Past = json_encode($jobs);
				}
				
				//Board & Advisor Roles
				$session = $html->find('div.advisory_roles', 0);
				if ($session != null) {
					$boardAdvisorRoles = array();
					foreach ($session->find('ul li div.info-block') as $div) {
						$item = $div->find('h4 a', 0);
						if ($item != null) {
							$value = $this->replaceHtmlChar($item->plaintext);
							foreach ($div->find('h5') as $a) {
								$text = $this->replaceHtmlChar($a->plaintext);
								if (!empty($text)) $value .= ' | ' . $text;
							}
							$boardAdvisorRoles[] = $value;
						}
					}
					$retVal->BoardAdvisorRoles = json_encode($boardAdvisorRoles);
				}
				
				//Education
				$session = $html->find('div.education', 0);
				if ($session != null) {
					$education = array();
					foreach ($session->find('ul li div.info-block') as $div) {
						$item = $div->find('h4 a', 0);
						if ($item != null) {
							$value = $this->replaceHtmlChar($item->plaintext);
							$text = $this->replaceHtmlChar($div->plaintext);
							$text = trim(str_replace($value, '', $text));
							$value .= ' | ' . $text;
							$education[] = $value;
						}
					}
					$retVal->Educations = json_encode($education);
				}
				return $retVal;
			}
		}
		return false;
	}
	
	private function getListCompany($cookieFileName, $rankBegin){
		$retVal = array();
		$json = $this->getHtml($cookieFileName,
			'https://www.crunchbase.com/v4/data/companies/search',
			'field_aggregators=[]
			&field_ids=["identifier"]
			&limit=1000
			&order=[{"field_id": "rank", "sort": "asc"}]
			&query=[{"type":"predicate","field_id":"rank","operator_id":"gte","values":['.$rankBegin.']}]');
		$json = @json_decode($json, true);
		if($json && isset($json['entities'])){
			foreach($json['entities'] as $item){
				$retVal[]=$item['properties']['identifier']['permalink'];
			}
		}
		return $retVal;
	}
	
	private function getListPeople($cookieFileName, $rankBegin){
		$retVal = array();
		$json = $this->getHtml($cookieFileName,
			'https://www.crunchbase.com/v4/data/people/search',
			'field_aggregators=[]
			&field_ids=["identifier"]
			&limit=1000
			&order=[{"field_id": "rank", "sort": "asc"}]
			&query=[{"type":"predicate","field_id":"rank","operator_id":"gte","values":['.$rankBegin.']}]');
		$json = @json_decode($json, true);
		if($json && isset($json['entities'])){
			foreach($json['entities'] as $item){
				$retVal[]=$item['properties']['identifier']['permalink'];
			}
		}
		return $retVal;
	}
	
	private function initCurl($url, $proxy, $userAgent){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		//proxy suport
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYPORT, '8081');
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'XXX:YYY');
		curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		
		curl_setopt($ch,CURLOPT_HTTPHEADER,array ("Accept: text/plain"));
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		set_time_limit (0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt ($ch, CURLOPT_USERAGENT, $userAgent);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		curl_setopt ($ch, CURLOPT_REFERER, $url);
		
		//GET RESULT
		if(curl_errno($ch)){
			curl_close($ch);
			return '';
		}
		$result = curl_exec ($ch);
		//execute the curl command
		$status = curl_getinfo($ch);
		curl_close($ch);
		if($status['http_code']==404) return "404";
		return $result;
	}
	
	private function getCookie($cookieFileName){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.crunchbase.com/v4/cb/sessions');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=k@bsml.net&password=reoi9091?');
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFileName);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFileName);
		curl_exec($ch);
		curl_close($ch);
		return true;
	}
	
	private function getHtml($cookieFileName, $url, $postdata = ''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFileName);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFileName);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");
		if (!empty($postdata)) {
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt ($ch, CURLOPT_POST, TRUE);
		}
		$html = curl_exec ($ch);
		curl_close ($ch);
		return $html;
	}
	
	private function replaceHtmlChar($text){
		$text = str_replace('&nbsp;', '', $text);
		$text = str_replace(array('&amp;', '&#038;'), '&', $text);
		$text = str_replace(array('&quot;', '&#8243;'), '"', $text);
		$text = str_replace('&#039;', "'", $text);
		return trim($text);
	}
	
	private function deCFEmail($c){
		$k = hexdec(substr($c,0,2));
		for ($i=2,$m='';$i<strlen($c)-1;$i+=2)$m.=chr(hexdec(substr($c,$i,2))^$k);
		return $m;
	}
	
	private function getProxiesAgents(){
		return array(
					'au1' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
					'au2' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
					'au4' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko',
					'at1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A',
		
					'br1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.1 Safari/537.36',
					'ca1' => 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0',
					'ca2' => 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0',
					'cl1' => 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25',
		
					'cr1' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36',
					'cz1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:33.0) Gecko/20100101 Firefox/33.0',
					'dk1' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 7.0; InfoPath.3; .NET CLR 3.1.40767; Trident/6.0; en-IN)',
					'eg1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
		
					'fi1' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2226.0 Safari/537.36',
					'fr1' => 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0',
					'de1' => 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))',
					'de2' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/534.55.3 (KHTML, like Gecko) Version/5.1.3 Safari/534.53.10',
		
					'hk1' => 'Mozilla/5.0 (Windows NT 6.4; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2225.0 Safari/537.36',
					'is1' => 'Mozilla/5.0 (Windows x86; rv:19.0) Gecko/20100101 Firefox/19.0',
					'in1' => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)',
					'in2' => 'Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko ) Version/5.1 Mobile/9B176 Safari/7534.48.3',
		
					'id1' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2224.3 Safari/537.36',
					'ie1' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20120101 Firefox/29.0',
					'im1' => 'Mozilla/4.0(compatible; MSIE 7.0b; Windows NT 6.0)',
					'il1' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; de-at) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
		
					'it1' => 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36',
					'jp1' => 'Mozilla/5.0 (X11; OpenBSD amd64; rv:28.0) Gecko/20100101 Firefox/28.0',
					'jp2' => 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
					'jp3' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; tr-TR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27',
		
					'lv1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36',
					'lt1' => 'Mozilla/5.0 (Windows NT 6.1; rv:27.3) Gecko/20130101 Firefox/27.3',
					'lu1' => 'Mozilla/4.0 (compatible; MSIE 6.1; Windows XP; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
					'my1' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; sv-SE) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4',
		
					'mx1' => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36',
					'md1' => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:27.0) Gecko/20121011 Firefox/27.0',
					'nl1' => 'Mozilla/4.0 (compatible; MSIE 6.01; Windows NT 6.0)',
					'nl2' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-HK) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5',
		
					'nz1' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.67 Safari/537.36',
					'nz2' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0',
					'pa1' => 'Mozilla/4.0 (compatible; MSIE 6.0b; Windows NT 5.1; DigExt)',
					'pl1' => 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/533.17.8 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8',
		
					'pt1' => 'Mozilla/5.0 (X11; OpenBSD i386) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36',
					'ro1' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/24.0',
					'ru1' => 'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
					'sg1' => 'Mozilla/5.0 (X11; U; Linux x86_64; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/531.2+',
		
					'za1' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1944.0 Safari/537.36',
					'kr1' => 'Mozilla/5.0 (Windows NT 6.2; rv:22.0) Gecko/20130405 Firefox/23.0',
					'es1' => 'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
					'se1' => 'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-en) AppleWebKit/533.16 (KHTML, like Gecko) Version/4.1 Safari/533.16',
		
					'ch1' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.3319.102 Safari/537.36',
					'tw1' => 'Mozilla/5.0 (Windows NT 6.2; rv:22.0) Gecko/20130405 Firefox/22.0',
					'tr1' => 'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
					'ua1' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en) AppleWebKit/526.9 (KHTML, like Gecko) Version/4.0dp1 Safari/526.8',
		
					'ae1' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2309.372 Safari/537.36',
					'uk1' => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/21.0.1',
					'uk2' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
					'uk3' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
		
					'us1' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2117.157 Safari/537.36',
					'us2' => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:21.0.0) Gecko/20121011 Firefox/21.0.0',
					'us3' => 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)',
					'us4' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ko-KR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27',
		
					'us5' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.47 Safari/537.36',
					'us6' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20130331 Firefox/21.0',
					'us7' => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
					'us8' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ja-JP) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4',
		
					'us9' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1866.237 Safari/537.36',
					'vn1' => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64;) Gecko/20100101 Firefox/20.0'
				);
	}
}
