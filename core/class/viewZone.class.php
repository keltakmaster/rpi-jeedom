<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class viewZone {
	/*     * *************************Attributs****************************** */

	private $id;
	private $view_id;
	private $type;
	private $name;
	private $position;
	private $configuration;

	/*     * ***********************Methode static*************************** */

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone
                WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byView($_view_id) {
		$value = array(
			'view_id' => $_view_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone
                WHERE view_id=:view_id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function removeByViewId($_view_id) {
		$value = array(
			'view_id' => $_view_id,
		);
		$sql = 'DELETE FROM viewZone
                WHERE view_id=:view_id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW);
	}

	/*     * *********************Methode d'instance************************* */

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function getviewData() {
		return viewData::byviewZoneId($this->getId());
	}

	public function getView() {
		return view::byId($this->getView_id());
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getView_id() {
		return $this->view_id;
	}

	public function setView_id($view_id) {
		$this->view_id = $view_id;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getPosition() {
		return $this->position;
	}

	public function setPosition($position) {
		$this->position = $position;
		return $this;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		return $this;
	}

}


