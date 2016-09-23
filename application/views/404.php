<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>404 page</title>
<link href="<?=$this->config->item('front_css_path')?>main.css" rel="stylesheet" type="text/css" />
<link href="<?=$this->config->item('fonts')?>stylesheet.css" rel="stylesheet" type="text/css" />
<link href="<?=$this->config->item('front_css_path')?>responsive.css" rel="stylesheet" type="text/css" />

</head>

<body class="pagenotfound">
<div class="page404">
  <div class="cotainer">
    <div class="logo404"><a href="<?= base_url()?>"><img src="<?=$this->config->item('image_path')?>logo2.png"  alt="" /></a></div>
    <h1><span>404</span><br />
      Not Found</h1>
  </div>
</div>
</body>
</html>
