<?php
/*
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 *      
 *      Ibnu Yahya <ibnu.yahya@toroo.org>
 */
	$app_name = "TCPanel";
	$app_desc = "Toroo Control Panel";
	$app_version = "1.0.1";
	$app_copyright = "Ibnu Yahya (Author)";
	$app_license = "GPL";
	$app_web = "http://toroo.org";
	$app_iconfile = "./image/toroopanel.png";

	$modules_dir="./modules";

	$doc_dir = "./doc";

	$app_license_text = file_get_contents ($doc_dir . "/copying");

	static $modules = array();

	$distro_name = "toroo-v3.1-cempaka";

	require_once ("functions.php");

	if (!class_exists ("gtk"))
	{
		//we should not doing this
		dl ("php_gtk2.so") or die ("Could not load GTK+ extension");
	};

	
?>
