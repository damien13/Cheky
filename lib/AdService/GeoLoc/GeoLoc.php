<?php

namespace AdService\GeoLoc;

class GeoLoc {


	const LABEL_DISTANCE_KM = "distance_km";
	const LABEL_LONGITUDE   = "longitude";
	const LABEL_LATITUDE    = "latitude";

	private $distanceKm;
	private $longitudeCityFrom;
	private $labelCityFrom;

	private $longitudeCityTo;
	private $latitudeCityFrom;
	private $latitudeCityTo;
	/**
	 * @return mixed
	 */
	public function getLabelCityFrom() {
		return $this->labelCityFrom;
	}

	/**
	 * @param mixed $labelCityFrom
	 */
	public function setLabelCityFrom($labelCityFrom) {
		$this->labelCityFrom = $labelCityFrom;
	}

	/**
	 * @return mixed
	 */
	public function getLabelCityTo() {
		return $this->labelCityTo;
	}

	/**
	 * @param mixed $labelCityTo
	 */
	public function setLabelCityTo($labelCityTo) {
		$this->labelCityTo = $labelCityTo;
	}
	private $labelCityTo;

	/**
	 * @return mixed
	 */
	public function getLongitudeCityFrom() {
		return $this->longitudeCityFrom;
	}

	/**
	 * @param mixed $longitudeCityFrom
	 */
	public function setLongitudeCityFrom($longitudeCityFrom) {
		$this->longitudeCityFrom = $longitudeCityFrom;
	}

	/**
	 * @return mixed
	 */
	public function getLongitudeCityTo() {
		return $this->longitudeCityTo;
	}

	/**
	 * @param mixed $longitudeCityTo
	 */
	public function setLongitudeCityTo($longitudeCityTo) {
		$this->longitudeCityTo = $longitudeCityTo;
	}

	/**
	 * @return mixed
	 */
	public function getLatitudeCityFrom() {
		return $this->latitudeCityFrom;
	}

	/**
	 * @param mixed $latitudeCityFrom
	 */
	public function setLatitudeCityFrom($latitudeCityFrom) {
		$this->latitudeCityFrom = $latitudeCityFrom;
	}

	/**
	 * @return mixed
	 */
	public function getLatitudeCityTo() {
		return $this->latitudeCityTo;
	}

	/**
	 * @param mixed $latitudeCityTo
	 */
	public function setLatitudeCityTo($latitudeCityTo) {
		$this->latitudeCityTo = $latitudeCityTo;
	}


	public function __construct() {
	}


	/**
	 * @return mixed
	 */
	public function getDistanceKm() {
		return $this->distanceKm;
	}

	/**
	 * @param mixed $distanceKm
	 */
	public function setDistanceKm($distanceKm) {
		$this->distanceKm = $distanceKm;
	}



}
