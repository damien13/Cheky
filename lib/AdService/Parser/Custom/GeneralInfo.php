<?php

namespace AdService\Parser\Custom;

use AdService\Ad;
use HttpClientCurl;
use Logger;

/**
 * Class GeneralInfo
 *
 * REQUEST ADDITIONAL DATA FROM DIFFERENT URL
 * For example : population from geo.api.gouv.fr
 *
 * @package AdService\Parser\Custom
 */


class GeneralInfo {

	private $_urlCurlPopulation = "https://geo.api.gouv.fr/communes?lat=#LAT#&lon=#LONG#&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre";
	private $_adList = array();
	private $_logger;
	private $_curl;

	/**
	 * GeneralInfo constructor.
	 *
	 * @param $adList array<Ad>
	 */
	public function __construct($adList){
		$this->_logger = Logger::getLogger("main");
		$this->_logger->info("GeneralInfo Parser initialize...");
		$this->_curl = new HttpClientCurl();
		$this->_adList = $adList;
	}


	/**
	 * @return array
	 */
	public function addInfo() {

		$adListResult = array();

		foreach ($this->_adList as $key => $ad) {
			$geoLoc = $ad->getGeoLoc();

			$url = str_replace("#LONG#", $geoLoc[0]->getLongitudeCityTo(), $this->_urlCurlPopulation);
			$url = str_replace("#LAT#", $geoLoc[0]->getLatitudeCityTo(), $url);
// todo if ville existante, ne pas requeter et copier
			$ad->setPopulation($this->parsePopulation($url));
			$this->_adList[$key] = $ad;
		}

		return $this->_adList;
	}


	/**
	 * @param $url
	 * @return number;
	 */
	private function parsePopulation($url) {

		$this->_curl->setUrl($url);
		$content = $this->_curl->request();

		$json   = json_decode($content, true);

		if(empty($json) || empty($json[0]['population'])){
			$this->_logger->warn("Population not found for " . $url);
			return 0;
		}

		return $json[0]['population'];
	}



}
