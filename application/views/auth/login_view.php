<div class="top-bg gradient-45deg-indigo-purple"></div>
<div class="login-form">
	<?php echo form_open("");?>
	    <div class="card">
	        <h3><?=$page_heading?></h3>
	        <div class="input-field">
	        	<?=form_input($username)?>
	            <label for="username">Username</label>
	            <span class="helper-text" data-error="Required field"></span>
	        </div>
	        <div class="input-field">
	        	<?=form_input($password)?>
	            <label for="password">Password</label>
	            <span class="helper-text" data-error="Required field"></span>
	        </div>
	        <?=form_hidden($csrf)?>
	        <?=form_button($submit)?>
	    </div>
	<?php echo form_close();?>
</div>