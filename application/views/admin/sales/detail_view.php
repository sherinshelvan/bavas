<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container edit-container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<?php echo form_open($action_url, 'id="sales_edit_form"');?>
			<div class="card">
				<div class="add-new left">
					<a href="<?=base_url($this->page_url)?>" class="waves-effect waves-light btn deep-purple darken-2"><i class="material-icons left">arrow_back</i></a>
				</div>
				<!-- <form method="post" action="<?=$action_url?>" id="sales_edit_form" enctype="multipart/form-data"> -->
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
						<h4 class="deep-purple-text">Basic Information</h4>
					</div>
					<table>
						<thead>
							<tr>
								<th>Sale Date</th>
								<th>Sales Man</th>
								<th>Price Type</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?=date("d-m-Y", strtotime($date['value']))?></td>
								<td><?=$selected_salesman?></td>
								<td><?=$result->price_type?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
			        	<div class="col s6 ">
			        		<h4 class="deep-purple-text">Extra Expenses</h4>
			        		<table>
			        			<thead>
			        				<tr>
			        					<th>Expense Title</th>
			        					<th>Amount</th>
			        				</tr>
			        			</thead>
			        			<tbody>
			        				<?php 
					        		if(isset($result) && isset($result->extra_expense) && is_array($result->extra_expense) && count($result->extra_expense) > 0){
					        			$result->total_expense_amount = 0;
					        			foreach ($result->extra_expense as $key => $value) {
					        				$result->total_expense_amount += $value['amount'];
					        				?>
						        			<tr>
							        			<td><?=$value['name']?></td>
							        			<td><?=number_format($value['amount'], 2)?></td>
							        		</tr>
					        				<?php
					        			}
					        		}
					        		else{
					        			?>
					        			<tr>
						        			<td colspan="2">
						        				No Extra expenses found.
						        			</td>
						        		</tr>
					        			<?php
					        		}
					        		?>
			        			</tbody>
			        		</table>
			        		
			        	</div>
			        	<div class="col s6">
			        		<h4 class="deep-purple-text">Summary</h4>
			        		<table>
			        			<thead>
			        				<tr>
				        				<th>Total Sale Amount</th>
				        				<th class="sales_total_amount">
				        					<?=number_format($result->sale_amount, 2)?>
				        				</th>
			        				</tr>
			        				<tr>
				        				<th>Total Expenses</th>
				        				<th class="expenses_total_amount">
											<?=number_format($result->total_expense_amount, 2)?>
				        				</th>
			        				</tr>
			        				<tr>
				        				<th>Credit Amount</th>
				        				<th class="credit_total_amount">
				        					<?=number_format($result->credit_amount, 2)?>
				        				</th>
			        				</tr>
			        				<tr>
				        				<th>Loss & Profit</th>
				        				<th class="cash_received">
				        					<?=(number_format(($result->sale_amount - $result->total_expense_amount - $result->credit_amount), 2))?>
				        				</th>
			        				</tr>
			        			</thead>
			        		</table>
			        	</div>
				    </div>
			</div>
			<div class="card">
			    <div class="row items-wrapper">
			        <div class="col s12">
		        		<h4 class="deep-purple-text">Sales Sheet</h4>
			        	<table class="sales-sheet table">
			        		<thead>
			        			<tr>
			        				<th>Product</th>
			        				<th>Price</th>
			        				<th>Quantity</th>
			        				<th>Return</th>
			        				<th>Damage</th>
			        				<th>Sale</th>
			        				<th>Amount</th>
			        			</tr>
			        		</thead>
			        		<tbody>
			        			<?php 
			        			if(isset($result) && isset($result->sales_data) && is_array($result->sales_data) && count($result->sales_data) > 0){
			        				$result->sale_amount = 0;
			        				foreach ($result->sales_data as $inc => $value) {
			        					$pos               = array_search($value['product_id'], $product_ids);
										$product_data      = $products_list[$pos];
										$value['quantity'] = (int)$value['quantity']?:0;
										$value['return']   = (int)$value['return']?:0;
										$value['damage']   = (int)$value['damage']?:0;
										$value['price']   = (int)$value['price']?:0;
										$sale = ($value['quantity'] - ($value['return'] + $value['damage']));
										$amount = $sale * $product_data->{$result->price_type};
										$result->sale_amount += $amount;
				        			?>
				        				<tr class="sales-item">
											<td><?=ucfirst($product_data->name)?></td>
											<td><?=number_format($value['price'], 2)?></td>
											<td><?=$value['quantity']?></td>
											<td><?=$value['return']?></td>
											<td><?=$value['damage']?></td>
											<td><?=$sale?></td>
											<td>
												<span class="amount"><?=number_format($amount, 2)?></span>
											</td>
										</tr>
				        			<?php 
			        				}
			        			}
			        			else{
			        				?>
			        				<tr class="no-result">
				        				<td colspan="7" style="text-align: center;">No items added.</td>
				        			</tr>
			        				<?php
			        			}
			        			?>
			        		</tbody>
			        		<tfoot>
			        			<tr>
			        				<th style="text-align: right;" colspan="6">Total Amount</th>
			        				<th  class="total-amount"><?=number_format($result->sale_amount, 2)?></th>
			        			</tr>
			        		</tfoot>
			        	</table>
			        </div>
			    </div>
			</div>				
			</div>
			<?php echo form_close();?>
			
		</div>
	</div>
</div>
<script type="text/javaScript">
	var sales_table = $("table.sales-sheet");
	function calculateTotalAmount(){
		var sales_total    = 0;
		var expenses_total = 0;
		var credit_amount  = $('input.credit_amount').val();
		sales_table.find("tbody tr.sales-item").each(function(index, element){
			var price      = $(element).find('input.price').val();
			var quantity   = $(element).find('input.quantity').val();
			var damage     = $(element).find('input.damage').val();
			var return_val = $(element).find('input.return').val();
			var sale = (Number(quantity) - (Number(damage) + Number(return_val)));
			var amount = Number(sale)*Number(price);
			sales_total = Number(sales_total, 2) + Number(amount, 2);
			$(element).find('input.sale').val(sale);
			$(element).find('input.amount').val(amount);
			$(element).find('span.sale').html(sale);
			$(element).find('span.amount').html(Number(amount, 2));
			$('th.sales_total_amount').html(Number(sales_total, 2));
			sales_table.find("tfoot tr th.total-amount").html(Number(sales_total, 2));
		});
		$('th.sales_total_amount').html(Number(sales_total, 2));
		sales_table.find("tfoot tr th.total-amount").html(Number(sales_total, 2));
		$("div.expense-items div.exp-item").each(function(e, element){
			var expense = $(element).find('input.amount ').val();
			expenses_total = Number(expenses_total, 2) + Number(expense, 2);
			$('th.expenses_total_amount').html(Number(expenses_total, 2));
		});
		$('th.expenses_total_amount').html(Number(expenses_total, 2));
		var cash_received = Number((sales_total - expenses_total - Number(credit_amount)), 2);
		$('th.cash_received').html(cash_received);
	}
	$(document).on("keyup", "input.no-insert", function(e){		
		calculateTotalAmount();		
	});
	$(document).on("keypress", "input.natural-no-validate", function(event){
		var val = $(this).val();
		if($(this).hasClass('float-amount') && isNaN( String.fromCharCode(event.keyCode) ) && String.fromCharCode(event.keyCode) != '.'){
			return false
		}
		else if ( !$(this).hasClass('float-amount') && isNaN( String.fromCharCode(event.keyCode) )  ) {
			return false
		};
	});
	function checkTableLength(){
		var rowCount = sales_table.find('tbody > tr').length;
		if(rowCount > 1){
			sales_table.find('tbody > tr.no-result').hide();
		}
		else{
			sales_table.find('tbody > tr.no-result').show();
		}
	}
	$(document).on("click", "span.expense-add", function(event){
		var id = Date.now();
		var item = '<div class="row exp-item">\
						<div class="col s7">\
							<input type="text" required name="extra_expense['+id+'][name]" placeholder="Expense" />\
						</div>\
						<div class="col s3">\
							<input type="text" required class="float-amount amount no-insert natural-no-validate" name="extra_expense['+id+'][amount]" placeholder="Amount" />\
						</div>\
						<div class="col s2"><i class="material-icons remove">close</i></div>\
					</div>';
		$("div.expense-items").append(item);
	});
	$(document).on("click", ".expense-items .exp-item .remove", function(event){
		$(this).parents('.exp-item').remove();
		calculateTotalAmount();
	});
	$(document).on("click", "table.sales-sheet tbody tr span.remove", function(event){
		$(this).parents('tr').remove();
		checkTableLength();
		calculateTotalAmount();
	});
	function updateSalesSheet(){
		var formdata = $("form#sales_edit_form").serialize();
		$.ajax({
			url : '<?=base_url("admin/".$this->page_url)?>/product_add',
			method : "POST",
			data : formdata
		}).done(function(response){
			// console.log(response);
			sales_table.find('tbody').html(response);
			checkTableLength();
			calculateTotalAmount();
		});
	}
	$(document).on("change", "select#price_type", function(event){
		updateSalesSheet();
	});
	$(document).on("change", "select#products", function(event){
		var products_list = '<?=json_encode($product_list)?>';
		if($(this).val() > 0){
			updateSalesSheet();
		}
	});
	$(document).ready(function() {
		$(document).ready(function(){
		    $('.sale-date').datepicker({format : 'dd-mm-yyyy', autoClose : true});
		});
    	$('textarea#description').characterCounter();
  	});
</script>