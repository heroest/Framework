<div class="jumbotron">
	<h1>Welcome!</h1>
	<p>Thank you for visiting my home page!</p>
</div>

<?php if(!empty($this->error)): ?>

<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Error!</strong>&nbsp;<br /><?php echo $this->error ?>
</div>

<?php endif; ?>

<form class="form-horizontal" role="form" action="<?php echo base_url('user/dologin');?>" method="post">
	<?php $this->security->csrf_input() ?>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="username">Username: </label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id="username" name="username" placeholder="USERNAME, 用户名">
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="password">Password: </label>
		<div class="col-sm-3">
			<input class="form-control" type="password" id="password" name="password" placeholder="PASSWORD, 密码">
		</div>
	</div>
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	    	<button type="submit" class="btn btn-primary">Login</button>
	    </div>
  	</div>
</form>

