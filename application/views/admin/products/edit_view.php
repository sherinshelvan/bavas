<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container edit-container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="card">
				<div class="add-new left">
					<a href="<?=base_url($this->page_url)?>" class="waves-effect waves-light btn deep-purple darken-2"><i class="material-icons left">arrow_back</i></a>
				</div>
				<?php echo form_open($action_url);?>
				<div class="row">
			        <div class="input-field col s12">
			          	<?=form_input($name)?>
			            <label for="name">Product Name</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s12">
			          	<?=form_textarea($description)?>
			            <label for="description">Description</label>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($company_price)?>
			            <label for="name">Company Price</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($commission_price)?>
			            <label for="name">Commission Price</label>
			        </div>
			        <!-- Switch -->
					<div class="switch input-field col s12">
					    <label>
					      Inactive
					      <?=form_checkbox($active)?>
					      <span class="lever"></span>
					      Active
					    </label>
					</div>
			        <div class="input-field col s12">
			        	<?=form_hidden($csrf)?>
	        			<?=form_button($submit)?>
			        </div>
			    </div>
				<?php echo form_close();?>
				
			</div>
			
		</div>
	</div>
</div>
<script type="text/javaScript">
	$(document).on("keypress", "input.natural-no-validate", function(event){
		var val = $(this).val();
		if ( isNaN( String.fromCharCode(event.keyCode) ) && String.fromCharCode(event.keyCode) != '.') {
			return false
		};
	});
	$(document).ready(function() {
    	$('textarea#description').characterCounter();
  	});
</script>