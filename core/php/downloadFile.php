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
try {
	require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');
	if (!isConnect()) {
		if (!jeedom::apiAccess(init('apikey'))) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
	}
	$pathfile = calculPath(urldecode(init('pathfile')));
	if (strpos($pathfile, '.php') !== false) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}
	$rootPath = realpath(dirname(__FILE__) . '/../../');
	if (strpos($pathfile, $rootPath) === false) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}
	if (strpos($pathfile, '*') === false) {
		if (!file_exists($pathfile)) {
			throw new Exception(__('Fichier non trouvé : ', __FILE__) . $pathfile);
		}
	} else if (is_dir(str_replace('*', '', $pathfile))) {
		system('cd ' . dirname($pathfile) . ';tar cfz ' . jeedom::getTmpFolder('downloads') . '/archive.tar.gz * > /dev/null 2>&1');
		$pathfile = jeedom::getTmpFolder('downloads') . '/archive.tar.gz';
	} else {
		$pattern = array_pop(explode('/', $pathfile));
		system('cd ' . dirname($pathfile) . ';tar cfz ' . jeedom::getTmpFolder('downloads') . '/archive.tar.gz ' . $pattern . '> /dev/null 2>&1');
		$pathfile = jeedom::getTmpFolder('downloads') . '/archive.tar.gz';
	}
	$path_parts = pathinfo($pathfile);
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $path_parts['basename']);
	readfile($pathfile);
	if (file_exists(jeedom::getTmpFolder('downloads') . '/archive.tar.gz')) {
		unlink(jeedom::getTmpFolder('downloads') . '/archive.tar.gz');
	}
	exit;
} catch (Exception $e) {
	echo $e->getMessage();
}

?>
