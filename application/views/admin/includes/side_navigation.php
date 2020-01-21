<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="side-navigation">
	<div id="fixed-header" class="">
		<div class="inner">
			<div class="logo"><a href="<?=base_url()?>" class="logo-container"><img src="<?=base_url('application/assets/images/logo.png')?>" /></a> </div>
			<a href="javascript:void(0);" data-target="main-menu" class="sidenav-trigger"> <i></i><i></i><i></i></a>

			<ul class="inline top-menu user-dropdown">
				<li><a class='dropdown-trigger' href='#' data-target='dropdown1'><span>Admin</span> <i class="material-icons">account_circle</i></a></li>
			</ul>
		</div>
	</div>
	<div id="header" class="dark">
		<div class="nav-wrapper">
			<ul id="main-menu" class="sidenav">
				<li>
					<a class="<?=$this->uri->segment(1) == 'dashboard' ? 'active' : ''?>" title="Dashboard" href="<?=base_url('dashboard')?>"><i class="material-icons">dashboard</i>Dashboard</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'products' ? 'active' : ''?>" title="Products" href="<?=base_url('products')?>"><i class="material-icons">apps</i>Products</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'sales' ? 'active' : ''?>" title="Sales" href="<?=base_url('sales')?>"><i class="material-icons">add_shopping_cart</i>Daily Sales </a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'departments' ? 'active' : ''?>" title="Departments" href="<?=base_url('departments')?>"><i class="material-icons">domain</i>Departments</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'users' ? 'active' : ''?>"  title="Staffs" href="<?=base_url('users')?>"><i class="material-icons">supervisor_account</i>Staffs </a>
				</li>

			</ul>
		</div>
	</div>
</div>

<!-- ============== Dropdown ====== -->

<!-- Dropdown Structure -->
<ul id='dropdown1' class='dropdown-content'>
	<li><a href="<?=base_url('edit-profile')?>"><i class="material-icons">account_box</i>Profile</a></li>
	<li class="divider" tabindex="-1"></li>
	<li><a href="<?=base_url('logout')?>"><i class="material-icons">exit_to_app</i>Logout</a></li>
	
</ul>