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
				<?php 
				if($message){
					?>
					<div class="row">
						<div class="col s12 red-text text-darken-2">
							<?=$message?>
						</div>
					</div>
					<?php
				}
				?>
				<div class="row">
					<div class="input-field col s12">
						<?=form_input($username)?>
			            <label for="username">Username</label>
			            <span class="helper-text" data-error="Required field"></span>
					</div>
			        <div class="input-field col s6">
			          	<?=form_input($first_name)?>
			            <label for="first_name">First Name</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($last_name)?>
			            <label for="last_name">Last Name</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($email)?>
			            <label for="email">Email</label>
			            <span class="helper-text" data-error="Please enter a valid email address"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($phone)?>
			            <label for="phone">Phone</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($password)?>
			            <label for="password">Password</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			          	<?=form_input($confirm_password)?>
			            <label for="confirm_password">Confirm Password</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			        <?php if ($this->ion_auth->is_admin() && isset($edit) && $edit): ?>			
			          <h3>Department</h3>
			          <?php foreach ($groups as $group): ?>
			          	<div class="input-field ">
			              <label class="checkbox">
			              <?php
			                  $gID=$group['id'];
			                  $checked = null;
			                  $item = null;
			                  foreach($currentGroups as $grp) {
			                      if ($gID == $grp->id) {
			                          $checked= ' checked="checked"';
			                      break;
			                      }
			                  }
			              ?>
			              <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
			              <span><?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?></span>
			              </label>
						</div>
			          <?php endforeach?>

			      	<?php endif ?>
			      	</div>
			      	<!-- Switch -->
					<div class="switch input-field col s12" style="margin-top:10px;">
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
    	$('input#name, textarea#description').characterCounter();
  	});
</script>