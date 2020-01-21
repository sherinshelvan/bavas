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
				<div class="row extra-filter">
					<div class="col s3">
						Search by Sales man
						<?=form_dropdown($sales_man)?>
					</div>
					<div class="col s3">
						From Date
						<?=form_input($from_date)?>
					</div>
					<div class="col s3">
						To Date
						<?=form_input($to_date)?>
					</div>
				</div>
				<table class="striped highlight datatable">
			       <thead>
				        <tr>
				          <th>ID</th>
				          <th>Date</th>
				          <th>Sales Man</th>
				          <th>Sale Amount</th>
				          <th>Expense Amount</th>
				          <th>Credit</th>
				          <th>Profit & Loss</th>
				          <th>Actions</th>
				        </tr>
				    </thead>
			      	<tfoot>
				      	<tr>
				          <th>ID</th>
				          <th>Date</th>
				          <th>Sales Man</th>
				          <th>Sale Amount</th>
				          <th>Expense Amount</th>
				          <th>Credit</th>
				          <th>Profit & Loss</th>
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
	var from_date = new Date('<?=$from_date['value']?>');
    var to_date   = new Date('<?=$to_date['value']?>');
    function dateRangeValidate(change){
      if(to_date < from_date && change == 'to_date'){
        from_date = to_date;
      } 
      if(to_date < from_date && change == 'from_date'){
        to_date = from_date;
      }      
      // initDate();
    }
    function initDate(){   
      $("input#from_date").datepicker({
        setDefaultDate : true,
        autoClose : true,
        defaultDate : new Date(from_date),
        format : "dd-mm-yyyy",
        onSelect : function(selected){
          from_date = selected;
          dateRangeValidate("from_date");
        }
      });
      $("input#to_date").datepicker({
        setDefaultDate : true,
        autoClose : true,
        defaultDate : new Date(to_date),
        format : "dd-mm-yyyy",
        onSelect : function(selected){
          to_date = selected;
          dateRangeValidate("to_date");
        }
      });
    }
    initDate();
 var dataTable = $('table.datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    "pageLength" : 50,
    "lengthMenu": [[-1, 50, 100, 300], ["All", 50, 100, 300]],
    'ajax': {
        'url':'<?=base_url("admin/".$this->page_url."/ajax_table")?>',
        'data': function(data){
          data.sales_man   = $("select#sales_man").val();
          data.from_date = $("input#from_date").val();
          data.to_date   = $("input#to_date").val();
        }
    },
    'order': [[ 1, 'desc' ]],
    'columnDefs' : [
      { orderable: true,  targets: [0, 1, 2, 3, 4, 5, 6] },
      { orderable: false, targets: '_all' }
    ],
    'columns': [
      { data: 'id' },
      { data: 'date' },
      { data: 'sales_man' },
      { data: 'sale_amount' },
      { data: 'total_expense_amount' },
      { data: 'credit_amount' },
      { data: 'loss_and_profit' },
      { data: 'actions' },
    ]
 });
 $(document).on("change", ".extra-filters", function(){
      dataTable.draw();
});
</script>