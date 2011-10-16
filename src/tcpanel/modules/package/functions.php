<?php
/*
 * package function
 * Noprianto, 2008
 * GPL.
 * modify ibnu yahya
 */


function package_list_pkg ($installed_pkg_fbody, $model, $label_pkg)
{
	while(gtk::events_pending()) { gtk::main_iteration(); }

	$f = create_function('',$installed_pkg_fbody);
	$installed_pkg = $f();

	$model -> clear();

	foreach ($installed_pkg as $pk => $pv)
	{

		$values = array(0);
		for ($i=0; $i<count ($pv); $i++)
		{
			$values[] = $pv[$i];
		}
		$model -> append ($values);
	}

	$info = count ($installed_pkg) . " installed packages found.";
	$label_pkg -> set_text ($info);
		
}

function package_install_pkg ($widget, $data, $cmd_i, $tv, $parent, $model, $label_pkg, $installed_pkg_fbody)
{


	$pkg_file = trim($data -> get_filename());
	if ($pkg_file == "")
	{
		$errmsg = "Please choose package file first.";
		$dialog = new GtkMessageDialog ($parent, 0, Gtk::MESSAGE_ERROR, Gtk:: BUTTONS_OK, $errmsg);

		$dialog -> set_markup ($errmsg);
		$dialog -> set_title ("Error occured");
		$dialog -> run();
		$dialog -> destroy ();
	}
	else
	{


		
		$tb = new GtkTextBuffer();
		$tb -> set_text ("Please wait...");
		$tv -> set_buffer ($tb);


		$real_cmd = $cmd_i . ' ' . $pkg_file;

		while(gtk::events_pending()) { gtk::main_iteration(); }
		$out = shell_exec ($real_cmd);
		$out .= "\n\nDone.";

		$tb -> set_text ($out);
		$tv -> set_buffer ($tb);
	
		package_list_pkg ($installed_pkg_fbody, $model, $label_pkg);
	}
}


function package_toggle ($widget, $row, $model)
{
	$iter = $model -> get_iter ($row);
	$val = $model -> get_value ($iter, 0);
	$model -> set ($iter, 0, !$val);
}

function package_uninstall_pkg ($widget,  $model, $parent, $cmd_e, $tv, $label_pkg, $installed_pkg_fbody)
{
	$packages = array();
	$rows = $model -> iter_n_children(NULL);
	for ($i=0; $i<$rows; $i++)
	{
		$iter = $model -> get_iter ($i);
		$check = $model -> get_value ($iter, 0);
		if ($check == 1)
		{
			$packages[] = $model -> get_value ($iter, 1);
		}
	}

	if (count ($packages) < 1)
	{
		$errmsg = "Please select package first.";
		$dialog = new GtkMessageDialog ($parent, 0, Gtk::MESSAGE_ERROR, Gtk:: BUTTONS_OK, $errmsg);

		$dialog -> set_markup ($errmsg);
		$dialog -> set_title ("Error occured");
		$dialog -> run();
		$dialog -> destroy ();
		
	}
	else
	{
		$tb = new GtkTextBuffer();
		$tb -> set_text ("Please wait...");
		$tv -> set_buffer ($tb);


		$real_cmd = $cmd_e;
		for ($i=0; $i<count ($packages); $i++)
		{
			$real_cmd .= " " . $packages[$i];
		}
		
		while(gtk::events_pending()) { gtk::main_iteration(); }

		$out = shell_exec ($real_cmd);
		$out .= "\n\nDone.";

		$tb -> set_text ($out);
		$tv -> set_buffer ($tb);
		
		package_list_pkg ($installed_pkg_fbody, $model, $label_pkg);
	}

}

/*
 * end of function
 */
?>
