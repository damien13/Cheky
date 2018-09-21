<?php

namespace AdService\Parser\Custom;

use AdService\Ad;
use HttpClientCurl;
use Logger;
use AdService\GeoLoc\GeoLoc;

class Glc {

	CONST JSON_INDEX_CITIES      = "stops";
	CONST JSON_INDEX_DISTANCE_KM = "distance";
	CONST JSON_INDEX_CITY_FROM   = 1;
	CONST JSON_INDEX_CITY_TO     = 0;
	CONST JSON_INDEX_LATITUDE    = "latitude";
	CONST JSON_INDEX_LONGITUDE   = "longitude";

	private $_adList = array();
	private $_logger;
	private $_urlBase = "https://fr.distance24.org/route.json?stops=#CITY1#|#CITY2#";
	private $_curl;


	private $_labelCityFrom = array(
		'salon-de-provence',
		'aix-en-provence'
	);

	public function __construct($adList) {
		$this->_logger = Logger::getLogger("main");
		$this->_logger->info("Wikipedia Parser initialize...");

		$this->_curl = new HttpClientCurl();

		$this->_adList = $adList;

	}

	/**
	 * @return array
	 */
	public function addInfo() {

		$adListResult = array();
		foreach ($this->_adList as $ad) {

			foreach ($this->_labelCityFrom as $cityNameFrom) {
				// use for city with district like PARIS 18EME
				// which is not recognized
				$hasDistrict = preg_match("/([a-zA-Z]+) \d+\w*/", $ad->getCity(), $matches);
				if (!empty($hasDistrict)) {
					$ad->setCityNoDistrict($matches[1]);
				}

				$cityToNoSpace = str_replace(" ", "-", $ad->getCity());
				$urlToFetch = str_replace("#CITY1#",$cityToNoSpace , $this->_urlBase);
				$urlToFetch = str_replace("#CITY2#", $cityNameFrom, $urlToFetch);

				$geoLoc = $this->parse($urlToFetch);
				// If no distance, essaie avec le regex
				if(empty($geoLoc->getDistanceKm())){
					$urlToFetch = str_replace("#CITY1#",strtolower($ad->getCityNoDistrict()), $this->_urlBase);
					$urlToFetch = str_replace("#CITY2#", $cityNameFrom, $urlToFetch);
					$geoLoc = $this->parse($urlToFetch);
				}
				// We fill the city name from/to
				$geoLoc->setLabelCityFrom(ucwords($cityNameFrom));
				$geoLoc->setLabelCityTo(ucwords($ad->getCity()));

				$ad->addGeoLoc($geoLoc);
			}

			$adListResult[] = $ad;
		}

		return $adListResult;
	}

	/**
	 * @param $urlToFetch string
	 * @return GeoLoc;
	 */
	private function parse($urlToFetch) {

		$this->_curl->setUrl($urlToFetch);
		$content = $this->_curl->request();

		$json   = json_decode($content, true);
		$geoLoc = new GeoLoc();

		if (isset($json[self::JSON_INDEX_DISTANCE_KM])) {
			$geoLoc->setDistanceKm(intval($json[self::JSON_INDEX_DISTANCE_KM]));
		}
		else {
			$this->_logger->warn("Distance not found for " . $urlToFetch);
		}

		if (isset($json[self::JSON_INDEX_CITIES])) {
			$geoLoc->setLatitudeCityFrom($json[self::JSON_INDEX_CITIES][self::JSON_INDEX_CITY_FROM][self::JSON_INDEX_LATITUDE]);
			$geoLoc->setLatitudeCityTo($json[self::JSON_INDEX_CITIES][self::JSON_INDEX_CITY_TO][self::JSON_INDEX_LATITUDE]);
			$geoLoc->setLongitudeCityFrom($json[self::JSON_INDEX_CITIES][self::JSON_INDEX_CITY_FROM][self::JSON_INDEX_LONGITUDE]);
			$geoLoc->setLongitudeCityTo($json[self::JSON_INDEX_CITIES][self::JSON_INDEX_CITY_TO][self::JSON_INDEX_LONGITUDE]);
		}
		else {
			$this->_logger->warn("Latitude/longitude not found for " . $urlToFetch);
		}


		return $geoLoc;
	}

}
