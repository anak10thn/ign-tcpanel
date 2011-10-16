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
require_once ("modules/network/functions.php");
if (module_init ($argv[0], __FILE__) == false) die ("module_init failed\n");
global $distro_name;
/*
 * end of don't edit
 */



/*
 * Distro check
 */

$iface_info = array ();
$cmd_start = "";
$cmd_stop = "";
$save_config_fbody = "";
$load_config_fbody = "";

require ("modules/network/distro_$distro_name.php");

/*
 * End of Distro Check
 *
 */


/*
 * main
 *
 */


if ($cmd_start == "" || $cmd_stop == "" || $save_config_fbody == "" || $load_config_fbody == "") 
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
	$network_pixbuf = GdkPixbuf :: new_from_file ("modules/network/network.png");

	$win = new GtkWindow ();
	$win -> set_title ("Network Configuration");
	$win -> connect_simple ("destroy", array ("Gtk", "main_quit"));
	$win -> set_size_request (600, 400);
	$win -> set_icon ($network_pixbuf);

	//main notebook
	//
	$nb = new GtkNotebook();
	//
	//

	//create 'iface' page 
	//
	$nb_iface = array();
	foreach ($iface_info as $k=> $v)
	{
		$temp_nb = new GtkFrame();
		$temp_nb_info = new GtkLabel ($k);
		$nb -> append_page ($temp_nb, $temp_nb_info);

		$temp_nb_table = new GtkTable (12, 12, true);

		$temp_nb_label = new GtkLabel ();
		$temp_nb_label -> set_alignment (0, 0.5);
		$temp_nb_label -> set_markup ("<b>$v</b>");

		$temp_nb_desc_arr = array ("IP Address", "Netmask", "DHCP?");

		$temp_nb_ipaddr = new GtkEntry();
		$temp_nb_netmask = new GtkEntry();
		$temp_nb_dhcp = new GtkCheckButton();

		$temp_nb_table -> attach ($temp_nb_label, 0, 12, 0, 2);
		for ($i=0; $i< count ($temp_nb_desc_arr); $i++)
		{
			$j = $i+1; 
			$temp_nb_desc = new GtkLabel ($temp_nb_desc_arr[$i]);
			$temp_nb_desc -> set_alignment (0, 0.5);

			$temp_nb_table -> attach ($temp_nb_desc, 0, 4, 2+$i, 2+$j);
		}

		$temp_nb_table -> attach ($temp_nb_ipaddr, 6, 12, 2, 3);
		$temp_nb_table -> attach ($temp_nb_netmask, 6, 12, 3, 4);
		$temp_nb_table -> attach ($temp_nb_dhcp, 6, 12, 4, 5);

		$temp_nb -> add ($temp_nb_table);

		$temp_nb_dhcp -> connect ("toggled", "network_dhcp_toggle", $temp_nb_ipaddr, $temp_nb_netmask);
	
		$nb_iface["$k"] = array ($temp_nb_ipaddr, $temp_nb_netmask, $temp_nb_dhcp);
	}
	//end of 'iface' page
	//
	//


	//Hostname, default gw and DNS page	
	//
	$label_host_desc_arr = array (
		"Host name",
		"Domain name",
		"Default gateway",
		"Name server 1",
		"Name server 2",
		"Name server 3",
		"Domain search 1",
		"Domain search 2",
		"Domain search 3",
	);
	
	$table_host = new GtkTable (8, 4, true);

	$label_host_arr = array();
	$entry_host_arr = array();
	for ($i = 0; $i < count ($label_host_desc_arr); $i++)
	{
		$j = $i+1;
		$v = $label_host_desc_arr[$i];
		$temp_host = new GtkLabel ("$v");
		$temp_host -> set_alignment (0, 0.5);
		$table_host -> attach ($temp_host, 0, 1, $i, $j);
		$label_host_arr[] = $temp_host;

		$temp_host2 = new GtkEntry();
		$table_host -> attach ($temp_host2, 1, 4, $i, $j);
		$entry_host_arr["$i"] = $temp_host2;
	}

	//
	//
	$nb_host = new GtkFrame();
	$nb_host -> add ($table_host);
	$nb_host_info = new GtkLabel ('Host name, Gateway and DNS');
	$nb -> append_page ($nb_host, $nb_host_info);
	//

	//end of hostname default gw, and DNS page
	//


	//action button
	//
	//
	$btn_save = new GtkButton ("_Save");
	$btn_stop = new GtkButton ("_Deactive all");
	$btn_start = new GtkButton ("_Activate all");

	$btn_save -> connect ("clicked", "network_save_config", $win, $nb_iface, $entry_host_arr, $save_config_fbody);
	$btn_start -> connect ("clicked", "network_control", $win, "start", $cmd_start, $cmd_stop);
	$btn_stop -> connect ("clicked", "network_control", $win, "stop", $cmd_start, $cmd_stop);
	//	


	//main table
	$table_main = new GtkTable (12, 12, true);
	$table_main -> attach ($nb, 0, 12, 0, 10);
	$table_main -> attach ($btn_save, 6, 8, 10, 11);
	$table_main -> attach ($btn_stop, 8, 10, 10, 11);
	$table_main -> attach ($btn_start, 10, 12, 10, 11);

	$win -> add ($table_main);

	network_load_config ($nb_iface, $entry_host_arr, $load_config_fbody);

	$win -> show_all ();

	Gtk :: main();
}

?>
