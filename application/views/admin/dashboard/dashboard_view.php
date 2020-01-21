<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?=base_url("application/libraries/chart-js/chart.bundle.min.js")?>"></script>
<!-- <script src="<?=base_url("application/libraries/chart-js/utils.js")?>"></script> -->
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="row">
				<div class="col s4">
					<div class="card">
						<h4 class="deep-purple-text center">Today's Sale Amount </h4>
						<h3 class="center pink-text text-accent-2">₹<?=number_format($today_sale->amount, 2)?></h3>
					</div>
				</div>
				<div class="col s4">
					<div class="card">
						<h4 class="deep-purple-text center">Today's Expense Amount</h4>
						<h3 class="center pink-text text-accent-2">₹<?=number_format($today_expense->amount, 2)?></h3>
					</div>
				</div>
				<div class="col s4">
					<div class="card">
						<h4 class="deep-purple-text center">Today's Profit & Loss </h4>
						<h3 class="center pink-text text-accent-2">₹<?=number_format($today_profit->amount, 2)?></h3>
					</div>
				</div>
				<div class="col s12">
					<div class="card" style="height: 250px; padding-bottom: 40px;">
					<h5 class="deep-purple-text center">Sale Graph - <?=date("d-m-Y")?></h5>
					<canvas style="" id="sale_chart" ></canvas>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var canvas = document.getElementById('sale_chart');
		var sale_chart = <?=json_encode($sale_graph)?>;
		var option = { responsive:true, maintainAspectRatio: false };
		var myBarChart = Chart.Bar(canvas, {
		  data: sale_chart,
		  options: option
		});
	});
	
</script>