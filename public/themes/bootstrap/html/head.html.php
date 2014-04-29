<?php
/**
 *  Head Partial
 *  Stuff between <head> tags
 */

# Templater object
$t = $this;
$t->page_title = NeechyConfig::get('title');
$t->meta_keywords = NeechyConfig::get('keywords');
$t->meta_description = NeechyConfig::get('description');

?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $t->page_title; ?></title>
    <meta name="keywords" content="<?php echo $t->meta_keywords; ?>" />
    <meta name="description" content="<?php  echo $t->meta_description; ?>" />
    <link rel="icon" type="image/x-icon"
		  href="themes/bootstrap/images/favicon.ico"  />
    <link rel="shortcut icon" type="image/x-icon"
		  href="themes/bootstrap/images/favicon.ico"  />

    <!-- Stylesheets -->
    <link rel="stylesheet"
      href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="themes/bootstrap/css/bootstrap-override.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
