<?php


namespace AdService\Wikipedia;


class WikipediaOpenSearch {

	const LABEL_DESCRIPTION = "description";
	const LABEL_LINK = "lien_wiki";

	private $description;

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getWikilink() {
		return $this->wikilink;
	}
	private $wikilink;


	public function __construct(){
	}


	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param mixed $wikilink
	 */
	public function setWikilink($wikilink) {
		$this->wikilink = $wikilink;
	}


}
