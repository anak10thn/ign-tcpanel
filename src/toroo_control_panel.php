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


require ("conf.php");

function show_about ($widget)
{
	global $app_name, $app_version, $app_desc, $app_copyright, $app_license_text, $app_web, $distro_name, $tpanel_pixbuf;

	$dlg_about = new GtkAboutDialog ();

	$dlg_about -> set_name ($app_name);
	$dlg_about -> set_version ($app_version);
	$dlg_about -> set_comments ($app_desc . "\n(" . $distro_name . ")");
	$dlg_about -> set_copyright ($app_copyright);
	$dlg_about -> set_license ($app_license_text);
	$dlg_about -> set_website ($app_web);
	$dlg_about -> set_logo ($tpanel_pixbuf);
	$dlg_about -> set_icon ($tpanel_pixbuf);

	$dlg_about -> run ();
	
	$dlg_about -> destroy ();


};

function run_module ($module_id)
{
	global $modules;
	global $modules_dir;
	global $argv;

	$module = $modules[$module_id];

	$module_main = $module["main"];
	
	$module_script = $modules_dir . "/" . $module["name"] . "/" . $module["main"];

	require ($module_script);

};

function process_module ($widget, $path, $model)
{
	global $modules;

	$module = $model[$path][1];

	foreach ($modules as $k => $v)
	{
		if ($module == $v["desc"])
		{
			run_module ($k);
			break;
		}
	};
};

function scan_modules ()
{
	global $modules;
	global $modules_dir;

	$uid = posix_getuid ();

	//get all modules
	if (is_dir ($modules_dir))
	{
		if ($dir_handle = opendir ($modules_dir))
		{
			while ( ($dir = readdir ($dir_handle)) !== false )
			{
				if ($dir != "." && $dir != "..")
				{
					$temp_init = $modules_dir . "/" . $dir . "/init.php";
					if (file_exists ($temp_init))
					{
				
						require ($temp_init);

						if ($uid != 0)
						{
							if ($tpanel_resource["access_level"] != "root")
							{
								$modules[] = $tpanel_resource;
								
							}
					
						}
						else
						{

							$modules[] = $tpanel_resource;
						}
					}
				}
			};

			closedir ($dir_handle);
		};
	};
}

//
//
//main
//
//
//

//main window
//
$tpanel_pixbuf = GdkPixbuf :: new_from_file ($app_iconfile);
$win_main = new GtkWindow ();
$win_main -> set_title ($app_desc);
$win_main -> set_size_request (520, 400);
$win_main -> connect_simple ("destroy", array ("Gtk", "main_quit"));
$win_main -> set_icon ($tpanel_pixbuf);
function fullscreen() {
	global $win_main;
	$win_main -> fullscreen();
}
//menubar and items
$menupop_file = new GtkMenu ();
$menu_file = new GtkMenuItem ("_File");
$menu_fullscreen = new GtkMenuItem ("_Fullscreen");
$menu_quit = new GtkMenuItem ("_Quit");
$menupop_file -> append ($menu_fullscreen);
$menupop_file -> append ($menu_quit);
$menu_file -> set_submenu ($menupop_file);
$menu_fullscreen -> connect_simple ("activate","fullscreen");
$menu_quit -> connect_simple ("activate", array ("Gtk", "main_quit"));

$menupop_help = new GtkMenu();
$menu_help = new GtkMenuItem ("_Help");
$menu_about = new GtkMenuItem ("_About");
$menupop_help -> append ($menu_about);
$menu_help -> set_submenu ($menupop_help);
$menu_about -> connect ("activate", "show_about");

$menubar_main = new GtkMenuBar ();
$menubar_main -> append ($menu_file);
$menubar_main -> append ($menu_help);

//iconview and liststore
$iv_main = new GtkIconView ();
$ivmodel_main = new GtkListStore (GdkPixBuf :: gtype, Gtk);
$iv_main -> set_model ($ivmodel_main);

$iv_main -> set_pixbuf_column (0);
$iv_main -> set_text_column (1);

$iv_main -> set_columns (0);
$iv_main -> set_item_width (150);

$iv_main -> connect ("item-activated", "process_module", $ivmodel_main);

scan_modules();

//draw icon 
foreach ($modules as $k => $v)
{
	$icon_file = $modules_dir . "/" . $v["name"] . "/" . $v["icon"];
	$pixbuf = GdkPixbuf :: new_from_file ($icon_file);
	$ivmodel_main -> set ($ivmodel_main -> append(), 0, $pixbuf, 1, $v["desc"]); 
};

//put in scrolled win
$scrollwin_main = new GtkScrolledWindow ();
$scrollwin_main -> set_policy (Gtk:: POLICY_AUTOMATIC, Gtk:: POLICY_AUTOMATIC);
$scrollwin_main -> add ($iv_main);


//status bar
if (posix_getuid () == 0)
{
	$msg_user_status = "Running as root. All modules available.";
}
else
{
	$msg_user_status = "Running as non-root user. Not all modules available.";
};

$statusbar_main = new GtkStatusBar ();
$cx_id = $statusbar_main -> get_context_id ('user_status');
$statusbar_main -> push ($cx_id, $msg_user_status);


//main table
$table_main = new GtkTable (11, 1, true);
$table_main -> attach ($menubar_main, 0, 1, 0, 1);
$table_main -> attach ($scrollwin_main, 0, 1, 1, 10);
$table_main -> attach ($statusbar_main, 0, 1, 10, 11);


//add main table, show them all
$win_main -> add ($table_main);

$win_main -> show_all ();


//process command line argument
if ($argc > 1)
{
	foreach ($modules as $k => $v)
	{
		if ($argv[1] == $v["name"])
		{
			run_module ($k);
			break;
		};		
	};
};

Gtk :: main();


?>
