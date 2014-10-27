<div class="page-header">
	<h2>Regsiter<small> *Email address is optional*</small></h1>
</div>


<?php if(!empty($this->error)): ?>

<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Error!</strong>&nbsp;<br /><?php echo $this->error ?>
</div>

<?php endif; ?>

<form class="form-horizontal" role="form" action="<?php echo base_url('doregister');?>" method="post">
	<?php $this->security->csrf_input() ?>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="username">Username: </label>
		<div class="col-sm-3">
			<input class="form-control" type="text" id="username" name="username" placeholder="[Required] USERNAME, 用户名" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="password">Password: </label>
		<div class="col-sm-3">
			<input class="form-control" type="password" id="password" name="password" placeholder="[Required] PASSWORD, 密码" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="verify">Verify: </label>
		<div class="col-sm-3">
			<input class="form-control" type="password" id="verify" name="verify" placeholder="[Required] Verify PASSWORD, 确认密码" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label" for="email">Email: </label>
		<div class="col-sm-3">
			<input class="form-control" type="email" id="email" name="email" placeholder="[Optional] Email, 电子邮箱" />
		</div>
	</div>
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	    	<button type="submit" class="btn btn-primary">Register</button>
	    </div>
  	</div>
</form>
