<?php
/*
 * Noprianto
 * Singkong 1.0 package helper script
 * 2008
 * modify ibnu yahya
 * GPL
 *
 */
$pkg_ext = array ("ext"=>"*.tpm", "desc" => "Toroo Package File (*.tpm)");
$cmd_i = "/sbin/installtpm  ";
$cmd_e = "/sbin/removepkg  ";


$installed_pkg_fbody = '

$dir = "/var/log/packages/";
$lines = 6;
$pkg_files = array();

if ($dh = opendir ($dir))
{
	while ( ($file = readdir ($dh)) !== false)
	{
		if ($file != "." && $file != "..")
		{
			$pkg_files[] = $file;
		}
	}
}

sort ($pkg_files);

for ($f=0; $f<count($pkg_files); $f++)
{
	$file = $pkg_files[$f];
	$fh = fopen ($dir . "/" . $file, "r");
	if ($fh)
	{
		for ($i=0; $i<$lines; $i++)
		{
			$line = fgets ($fh);
			if ($i == 2)
			{
				$sizearr = explode (":", $line);
				$size =	trim($sizearr[1]);
			}
			else
			if ($i == 5)
			{
				$descarr = explode (":", $line);
				$name = trim($descarr[0]);
				$desc = trim($descarr[1]);
			}
		}
		fclose ($fh);
	}
	$fcarr = array ($name, $desc, $size);
	$installed_pkg[] = $fcarr;

}
return $installed_pkg;
';




?>
