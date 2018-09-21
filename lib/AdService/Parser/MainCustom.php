<?php

namespace AdService\Parser;

/**
 *  THIS CLASS IS USED TO GATHER ALL THE CUSTOM PARSER FOR GETTING EXTRA DATA
 *  IT CAN BE DISTANCE BETWEEN TWO CITY, WIKIPEDIA DESCRIPTION etc.
 *
 *
 */

use AdService\Parser\Custom\GeneralInfo;
use AdService\Parser\Custom\Glc;
use AdService\Parser\Custom\Wikipedia;
use HttpClientCurl;
use Logger;

class MainCustom {

	private $_adList;
	private $_logger;
	private $_curl;

	public function __construct($adList){
		$this->_logger = Logger::getLogger("main");
		$this->_curl = new HttpClientCurl();
		$this->_adList = $adList;
		$this->requestAll();
	}


	/**
	 * @return void
	 */
	private function requestAll() {

		$geoLoc           = new Glc($this->_adList);
		$this->_adList    = $geoLoc->addInfo();

		$generalInfo = new GeneralInfo($this->_adList);
		$this->_adList = $generalInfo->addInfo();
		//$wikipedia = new Wikipedia($this->_adList);
		//$this->_adList = $wikipedia->addInfo();
	}

	public function getAdList(){
		return $this->_adList;
	}



}
