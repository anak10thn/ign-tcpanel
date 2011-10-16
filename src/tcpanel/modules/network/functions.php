<?php
/*
 * package function
 * Noprianto, 2008
 * GPL.
 * modify ibnu yahya
 */

function network_save_config ($widget, $win, $nb_iface, $entry_host_arr, $save_config_fbody)
{
	$iface_arr = array();
	foreach ($nb_iface as $k=> $v)
	{
		$iface_arr["$k"] = array($v[0] -> get_text(), $v[1] -> get_text(), $v[2] -> get_active());
	}

	$e =  $entry_host_arr;
	$hostname = $e[0] -> get_text();
	$domain = $e[1] -> get_text();
	$gw = $e[2] -> get_text ();

	$ns1 = $e[3] -> get_text();
	$ns2 = $e[4] -> get_text();
	$ns3 = $e[5] -> get_text();


	$search1 = $e[6] -> get_text();
	$search2 = $e[7] -> get_text();
	$search3 = $e[8] -> get_text();

	$host_arr = array ($hostname, $domain, $gw, $ns1, $ns2, $ns3, $search1, $search2, $search3);

	$save_func = create_function  ('$iface_arr, $host_arr', $save_config_fbody);
	$save_func ($iface_arr, $host_arr);


	//dialog
	$msg = "Configuration saved.";
	$dialog = new GtkMessageDialog ($win, 0, Gtk::MESSAGE_INFO, Gtk:: BUTTONS_OK, $msg);

	$dialog -> set_markup ($msg);
	$dialog -> set_title ("Information");
	$dialog -> run();
	$dialog -> destroy ();
}

function network_dhcp_toggle ($widget, $entry_ip, $entry_netmask)
{
	if ($widget -> get_active() == true)
	{
		$entry_ip -> set_sensitive (false);
		$entry_netmask -> set_sensitive (false);
	}
	else
	{
		$entry_ip -> set_sensitive (true);
		$entry_netmask -> set_sensitive (true);
	}
}


function network_load_config ($nb_iface, $entry_host_arr, $load_config_fbody)
{

	$load_func = create_function  ('$nb_iface', $load_config_fbody);
	$ret = $load_func ($nb_iface);

	$iface_arr = $ret[0];
	$host_arr = $ret[1];

	$i = 0;
	foreach ($nb_iface as $k=> $v)
	{
		$ip = str_replace ("\"", "", $iface_arr[$i][0]);
		$nm = str_replace ("\"", "", $iface_arr[$i][1]);
		

		$dhcp = $iface_arr[$i][2];
		$v[0] -> set_text ($ip);

		if ($nm == "") $nm = "255.255.255.0";	
		$v[1] -> set_text ($nm);
		
		if ($dhcp == "\"yes\"")
		{
			$v[2] -> set_active (true);
		}	
		else
		{
			$v[2] -> set_active (false);
		}

		$i++;
	}


	for ($i=0; $i < count ($host_arr); $i++)
	{
		$temp = str_replace ("\"", "", $host_arr[$i]);
		$entry_host_arr[$i] -> set_text ($temp);
	}


}

function network_control ($widget, $win, $mode, $cmd_start, $cmd_stop)
{
	$win -> set_sensitive (false);
	
	while(gtk::events_pending()) { gtk::main_iteration(); }


	if ($mode == "start")
	{
		shell_exec ($cmd_start);
	}
	else		
	if ($mode == "stop")
	{
		shell_exec ($cmd_stop);
	}
	
	$win -> set_sensitive (true);
}


?>
