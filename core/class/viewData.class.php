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

class viewData {
	/*     * *************************Attributs****************************** */

	private $id;
	private $order = 0;
	private $viewZone_id;
	private $type;
	private $link_id;
	private $configuration;

	/*     * ***********************Methode static*************************** */

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byTypeLinkId($_type, $_link_id) {
		$value = array(
			'type' => $_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE type=:type
                    AND link_id=:link_id
                ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byviewZoneId($_viewZone_id) {
		$value = array(
			'viewZone_id' => $_viewZone_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE viewZone_id=:viewZone_id
                ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchByConfiguration($_search) {
		$value = array(
			'search' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE configuration LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function removeByTypeLinkId($_type, $_link_id) {
		$viewDatas = self::byTypeLinkId($_type, $_link_id);
		foreach ($viewDatas as $viewData) {
			$viewData->remove();
		}
		return true;
	}

	/*     * *********************Methode d'instance************************* */

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function getviewZone() {
		return viewZone::byId($this->getviewZone_id());
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
		return $this;
	}

	public function getviewZone_id() {
		return $this->viewZone_id;
	}

	public function setviewZone_id($viewZone_id) {
		$this->viewZone_id = $viewZone_id;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function getLink_id() {
		return $this->link_id;
	}

	public function setLink_id($link_id) {
		$this->link_id = $link_id;
		return $this;
	}

	public function getLinkObject() {
		$type = $this->getType();
		if (class_exists($type)) {
			return $type::byId($this->getLink_id());
		}
		return false;
	}

	public function getConfiguration($_key = '', $_default = '') {
		if ($this->configuration == '') {
			return $_default;
		}
		if (@json_decode($this->configuration, true)) {
			if ($_key == '') {
				return json_decode($this->configuration, true);
			}
			$options = json_decode($this->configuration, true);
			return (isset($options[$_key])) ? $options[$_key] : $_default;
		}
		return $_default;
	}

	public function setConfiguration($_key, $_value) {
		if ($this->configuration == '' || !@json_decode($this->configuration, true)) {
			$this->configuration = json_encode(array($_key => $_value));
		} else {
			$options = json_decode($this->configuration, true);
			$options[$_key] = $_value;
			$this->configuration = json_encode($options);
		}
		return $this;
	}

}
