<div class="page-header">
  <h3>Message board<small>&nbsp;feel free to leave message</small></h3>
</div>

<?php foreach($posts as $post): ?>
	
	<div class="panel panel-info">
		<div class="panel-heading">
			<?php echo '<strong>' . $post['title'] . '</strong> @ ' . $post['datetime'] . ' by [' . $post['poster'] . ']'; ?>
		</div>
		<div class="panel-body">
			<?php echo $post['text'];?>
		</div>
	</div>
	<br />
<?php endforeach; ?>

<hr />
<?php if(!empty($this->error)): ?>

<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Error!</strong>&nbsp;<br /><?php echo $this->error ?>
</div>

<?php endif; ?>
<form action="<?php echo base_url('index/newpost');?>" method="post" role="form" style="max-width:60%">
	<!-- CSRF -->
	<?php $this->security->csrf_input(); ?>
	<h4><span class="label label-primary">Title </span></h4>
	<input type="text" class="form-control" name="title" placeholder="Title of message"><br />
	<h4><span class="label label-primary">Text </span></h4>
	<textarea class="form-control" rows="5" name="text" placeholder="Text of message"></textarea>
	<br />
	<button type="submit" class="btn btn-primary">Leave message</button>
</form>