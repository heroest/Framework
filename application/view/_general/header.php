<!DOCTYPE html>
<html>
<head>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="<?php echo base_url('asset/js/bootstrap.min.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('asset/css/bootstrap.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('asset/css/bootstrap-theme.min.css'); ?>">
  <style>
    body{
      background-image: url("<?php echo base_url('asset/img/background.jpg')?>");
    }
  </style>
  
</head>
<title>
	<?php echo $page['title']; ?> - Message board
</title>



<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url();?>">HOME</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li><a href="<?php echo base_url('code/viewcode');?>">Code</a></li>
      <?php if(!$this->session->get('userlogin')): ?>
        <li><a href="<?php echo base_url('login');?>">Login</a></li>  
        <li><a href="<?php echo base_url('register');?>">Register</a></li>
        </ul>
      <?php else: ?>
        <li><a href="<?php echo base_url('logout/' . $this->session->get('logout_salt'))?>">Logout <span class="glyphicon glyphicon-off"></span></a></li>
        </ul>
        <p class="navbar-text">[ Welcome, <?php echo $this->session->get('userlogin');?> ]</p>
      <?php endif; ?> 
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<body>
  <div class="body-main">