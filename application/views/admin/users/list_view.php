<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container container-list">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="card">
				<div class="add-new right">
					<a href="<?=base_url($this->page_url)?>/add" class="waves-effect waves-light btn deep-purple darken-2"><i class="material-icons left">add</i>Add New</a>
				</div>
				<table class="striped highlight datatable">
			       <thead>
				        <tr>
				          <th>ID</th>
				          <th>First Name</th>
				          <th>Last Name</th>
				          <th>Email</th>
				          <th>Phone</th>
				          <th>Status</th>
				          <th>Actions</th>
				        </tr>
				    </thead>
			      	<tfoot>
				      	<tr>
				          <th>ID</th>
				          <th>First Name</th>
				          <th>Last Name</th>
				          <th>Email</th>
				          <th>Phone</th>
				          <th>Status</th>
				          <th>Actions</th>
				        </tr>
			     	 </tfoot>
			    </table>
				
			</div>
			
		</div>
	</div>
</div>
 <!-- Modal Structure -->
<div id="deleteModal" class="modal delete confirmation">
	<div class="modal-content center">
	  <span class="close-img"><i class="material-icons">close</i></span>
	  <h2>Are you sure you want to delete this item?</h2>
	  <a href="#!" data-link="<?=base_url($this->page_url)?>/delete/" class="waves-effect red lighten-1 btn delete confirm">Delete</a>
	  <a href="#!" class="modal-close waves-effect btn teal lighten-2">No</a>
	</div>
</div>
<script type="text/javaScript">
 $('table.datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "lengthMenu": [[-1, 50, 100, 300], ["All", 50, 100, 300]],
    'ajax': {
        'url':'<?=base_url("admin/".$this->page_url."/ajax_table")?>'
    },
    'order': [[ 0, 'desc' ]],
    'columnDefs' : [
      { orderable: true,  targets: [0, 1, 2] },
      { orderable: false, targets: '_all' }
    ],
    'columns': [
      { data: 'id' },
      { data: 'first_name' },
      { data: 'last_name' },
      { data: 'email' },
      { data: 'phone' },
      { data: 'status' },
      { data: 'actions' },
    ]
 });
</script>