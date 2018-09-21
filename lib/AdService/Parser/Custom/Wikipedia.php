<?php

namespace AdService\Parser\Custom;

use AdService\Exception;
use Logger;
use HttpClientCurl;

class Wikipedia {


	const JSON_INDEX_DESCRIPTION_LIST = 2;
	const JSON_INDEX_WIKIPEDIA_LINK_LIST = 3;

	private $_adList = array();
	private $_urlBase = "https://fr.wikipedia.org/w/api.php?action=opensearch&search=#CITY#&format=json";
	private $_logger;
	private $_curl;

	/**
	 * Wikipedia constructor.
	 *
	 */
	public function __construct($adList) {
		$this->_logger = Logger::getLogger("main");
		$this->_logger->info("Wikipedia Parser initialize...");

		$this->_curl = 	new HttpClientCurl();
		$this->_adList = $adList;
	}


	/**
	 * @param array $adList
	 * @return array
	 */
	public function addInfo() {

		$adListResult = array();

		foreach ($this->_adList as $ad) {

			$wikipediaOpenSearch = $this->parseGeneralInfo($ad->getCity());
			$wikipediaOpenSearch = $this->parseWikipediaPage($wikipediaOpenSearch);

			$adListResult[] = $ad;
		}

		return $adListResult;
	}

	/**
	 *  Add population and description
	 *
	 * @param $city
	 * @return null | \AdService\Wikipedia\WikipediaOpenSearch
	 */
	private function parseGeneralInfo($city) {

		$urlToFetch = str_replace("#CITY#", $city, $this->_urlBase);
		$url = str_replace(" ", "-", $urlToFetch);
		$this->_curl->setUrl($url);
		$content = $this->_curl->request();

		$json = json_decode($content, true);
		if (!empty($json[self::JSON_INDEX_DESCRIPTION_LIST])) {

			$found = 0;
			$indexRightCity = 0;
			for($i = 0; $i < count($json[self::JSON_INDEX_DESCRIPTION_LIST]); $i++){

				if(strpos(reset($json[self::JSON_INDEX_DESCRIPTION_LIST]), "commune") > -1) {
					$found = 1;
					$indexRightCity = $i;
					break;
				}

			}

			if(empty($found)) {
				$this->_logger->warn("1 - Wikipedia Parser : Commune not found for ". $city);
				return null;
			}

			$description = $json[self::JSON_INDEX_DESCRIPTION_LIST][$indexRightCity];
			$wikipediaLink = $json[self::JSON_INDEX_WIKIPEDIA_LINK_LIST][$indexRightCity];

			$wikipediaOpenSearch = new \AdService\Wikipedia\WikipediaOpenSearch();
			$wikipediaOpenSearch->setDescription($description);
			$wikipediaOpenSearch->setWikilink($wikipediaLink);

			return $wikipediaOpenSearch;

		}else {
			$this->_logger->warn("2 - Wikipedia Parser : Array empty for ". $city);
		}

		return null;
	}

	/**
	 * @param \AdService\WikipediaOpenSearch $wikipediaOpenSearch
	 * @throws Exception
	 * @return \AdService\WikipediaOpenSearch
	 */
	private function parseWikipediaPage($wikipediaOpenSearch){

		if(empty($wikipediaOpenSearch)){
			return null;
		}

		if(empty($wikipediaOpenSearch->getWikilink())){
			$this->_logger->error("3 - Wiki url is empty");
			throw new Exception("Wiki url is empty", 3);
		}


		$content = $this->_curl->request($wikipediaOpenSearch->getWikilink());





	}
}
