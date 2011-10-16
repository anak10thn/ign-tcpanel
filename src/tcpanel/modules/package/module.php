<?php
/*
 * Noprianto, 2008.
 * GPL.
 */

/*
 *
 * don't edit
 */
require ("conf.php");
require_once ("modules/package/functions.php");
if (module_init ($argv[0], __FILE__) == false) die ("module_init failed\n");
global $distro_name;
/*
 * end of don't edit
 */



/*
 * Distro check
 */

$pkg_ext = array ();
$installed_pkg_fbody = "";
$cmd_i = "";
$cmd_e = "";

require ("modules/package/distro_$distro_name.php");

/*
 * End of Distro Check
 *
 */


/*
 * main
 *
 */


if ($cmd_i == "" || $cmd_e == "" || count ($pkg_ext) < 1 || $installed_pkg_fbody == "") 
{
	$errmsg = "Insufficient information.";
	$dialog = new GtkMessageDialog (null, 0, Gtk::MESSAGE_ERROR, Gtk:: BUTTONS_OK, $errmsg);

	$dialog -> set_markup ($errmsg);
	$dialog -> set_title ("Error occured");
	$dialog -> run();
	$dialog -> destroy ();


}
else
{
	$package_pixbuf = GdkPixbuf :: new_from_file ("modules/package/package.png");

	$win = new GtkWindow ();
	$win -> set_title ("Package Management");
	$win -> connect_simple ("destroy", array ("Gtk", "main_quit"));
	$win -> set_size_request (600, 400);
	$win -> set_icon ($package_pixbuf);


	$nb = new GtkNotebook();


	//first page	
	//tree view 
	//
	//
	$model = new GtkListStore (TYPE_BOOLEAN, TYPE_STRING, TYPE_STRING, TYPE_LONG);
	$header = array ("Package", "Description", "Size");

	$treev = new gtkTreeView ($model);
	
	$cell_renderer = new GtkCellRendererToggle();
	$cell_renderer -> set_property ("activatable", true);
	$column  = new GtkTreeViewColumn ("select", $cell_renderer, "active", 0);
	$cell_renderer -> connect ("toggled", "package_toggle", $model);
	$treev -> append_column ($column);  

	//header
	for ($i=0; $i<count ($header); $i++)
	{
		$cell_renderer = new GtkCellRendererText();
		$column = new GtkTreeViewColumn ($header[$i], $cell_renderer, 'text', $i+1);
		$treev -> append_column ($column);
	}


	//scrolled win
	$scrolled_win_list = new GtkScrolledWindow ();
	$scrolled_win_list -> set_policy (Gtk :: POLICY_AUTOMATIC, Gtk :: POLICY_AUTOMATIC);
	$scrolled_win_list -> add ($treev);



	$textv_remove = new GtkTextView();
	$textv_remove -> set_editable (false);
	$scrolled_win_remove = new GtkScrolledWindow ();
	$scrolled_win_remove -> set_policy (Gtk :: POLICY_AUTOMATIC, Gtk :: POLICY_AUTOMATIC);
	$scrolled_win_remove -> add ($textv_remove);
	
	$label_pkg = new GtkLabel();

	$btn_uninstall = new GtkButton ("_Uninstall");
	$btn_uninstall -> connect ("clicked", "package_uninstall_pkg", $model, $win, $cmd_e, $textv_remove, $label_pkg, $installed_pkg_fbody);


	//attach to list's main table
	$table_list = new GtkTable (20, 12, true);
	$table_list -> attach ($scrolled_win_list, 0, 12, 0, 14);
	$table_list -> attach ($scrolled_win_remove, 0, 12, 14, 18);
	$table_list -> attach ($label_pkg, 0, 5, 18, 20);
	$table_list -> attach ($btn_uninstall, 9, 12, 18, 20);
	// end of first page
	//


	//second page
	//

	$filter = new GtkFileFilter();
	$filter -> set_name ($pkg_ext["desc"]);
	$filter -> add_pattern ($pkg_ext["ext"]);

	$btn_pkg = new GtkFileChooserButton ("Select package", Gtk::FILE_CHOOSER_ACTION_OPEN);
	$btn_pkg -> add_filter ($filter);

	$textv_install = new GtkTextView();
	$textv_install -> set_editable (false);

	//scrolled win
	$scrolled_win_new = new GtkScrolledWindow ();
	$scrolled_win_new -> set_policy (Gtk :: POLICY_AUTOMATIC, Gtk :: POLICY_AUTOMATIC);
	$scrolled_win_new -> add ($textv_install);

	$btn_install = new GtkButton ("_Install");
	$btn_install -> connect ("clicked", "package_install_pkg", $btn_pkg, $cmd_i, $textv_install, $win, $model, $label_pkg, $installed_pkg_fbody);

	$table_new = new GtkTable (12, 12, true);
	$table_new -> attach ($btn_pkg, 0, 9, 0, 2);
	$table_new -> attach ($btn_install, 9, 12, 0, 2);
	$table_new -> attach ($scrolled_win_new, 0, 12, 2, 12);
	//end of second page
	//
	

	//main frames
	$nb_list = new GtkFrame();
	$nb_list -> add ($table_list);
	$nb_list_info = new GtkLabel ('Installed packages');
	$nb -> append_page ($nb_list, $nb_list_info);

	$nb_new = new GtkFrame();
	$nb_new -> add ($table_new);
	$nb_new_info = new GtkLabel ('Install new package');
	$nb -> append_page ($nb_new, $nb_new_info);
	//end of main frames
	//
	//

	$win -> add ($nb);


	$win -> show_all ();

	//get installed packages
	//make it the last, to ensure all widgets are shown.
	package_list_pkg ($installed_pkg_fbody, $model, $label_pkg);

	Gtk :: main();
}

?>
