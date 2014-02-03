<?php

namespace CFPropertyList;

$basepath = 'http://localhost:8888/munki'; // Path to the munkirepo
$catalog = 'production'; // Catalog name
$apps = array();

error_reporting( E_ALL );
ini_set( 'display_errors', 'on' );



require_once('CFPropertyList/classes/CFPropertyList/CFPropertyList.php');

// Parse catalog
$production_cat = new CFPropertyList();
$production_cat->parse(file_get_contents("$basepath/catalogs/$catalog"));

// Fill apps array
foreach($production_cat->toArray() AS $key => $val)
{
	if(isset($apps[$val['name']]) && $apps[$val['name']]['version'] > $val['version'])
	{
		continue; // Item with higher version already in list
	}
	
	$apps[$val['name']] = array('version' => $val['version']);
	$apps[$val['name']]['display_name'] = isset($val['display_name']) ? $val['display_name'] : $val['name'];
	$apps[$val['name']]['desc'] = isset($val['description']) ? $val['description'] : '';
}

sort($apps);

?>

<!doctype html>
<html lang=en>
<head>
<meta charset=utf-8>
<title>Mac OSX Applications</title>
<style type="text/css" media="screen">
	body
	{
		margin: 0 0 40px 0;
		padding: 0;
		background: #fff;
		font-family: Arial, Helvetica, sans-serif;
	    font-size: 1em;
	    line-height: 1.4;
	}
	h1, p, h2
	{
		margin-left: 20px;
		margin-right: 20px;
	}
	.wrapper
	{
		width: 966px; 
		padding: 0 0 20px 0;
		margin: auto;
		box-shadow:0px 0px 12px #333;
	}
	.logo
	{
		display: block;
		height: 96px;
		background:rgb(25, 148, 212);
		border: 0;
		font: 0/0 a;
		text-shadow: none;
		color: transparent;
	}
	.error
	{
		padding: 4px;
		background: #fee;
		color: #f33;
		border: 1px solid #f00;
	}
	.clearfix:before,
	.clearfix:after {
	    content: "";
	    display: table;
	}

	.clearfix:after {
	    clear: both;
	}

	/*
	 * For IE 6/7 only
	 * Include this rule to trigger hasLayout and contain floats.
	 */

	.clearfix {
	    *zoom: 1;
	}
	
</style>
</head>
<body>
	<div class="wrapper clearfix">
		<a class="logo" href="#">To the company website</a>
	
			
	<h1>Catalog <?=$catalog?></h1>
	
	<?php foreach($apps AS $app):?>
		<h2><?php echo $app['display_name'].' ('.$app['version'].')'?></h2>

		<?if(strpos($app['desc'], 'html>')):?>

		<?php echo str_replace("html>", "p>", $app['desc'])?>

		<?else:?>

		<p><?php echo str_replace("\n", "<br>\n", $app['desc'])?></p>

		<?endif?>

	<?php endforeach?>
	
		</div>
</body>
</html>
