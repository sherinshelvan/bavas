<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
$exist_data = [];
$inc = 0;
foreach ($sales_data as $key => $value) {
	if(in_array($value['product_id'], $product_ids)){
		$pos               = array_search($value['product_id'], $product_ids);
		$product_data      = $products_list[$pos];
		$value['quantity'] = (int)$value['quantity']?:0;
		$value['return']   = (int)$value['return']?:0;
		$value['damage']   = (int)$value['damage']?:0;
		$sale = ($value['quantity'] - ($value['return'] + $value['damage']));
		$amount = $sale * $product_data->{$price_type};
	?>
		<tr class="sales-item">
			<td>
				<?=ucfirst($product_data->name)?>
				<input type="hidden" class="product_id natural-no-validate" name="sales_data[<?=$inc?>][product_id]" value="<?=$product_data->id?>" />
			</td>
			<td>
				<?=number_format($product_data->{$price_type}, 2)?>
				<input type="hidden" class="price natural-no-validate" name="sales_data[<?=$inc?>][price]" value="<?=$product_data->{$price_type}?>" />
			</td>
			<td><input type="text" class="quantity no-insert natural-no-validate" name="sales_data[<?=$inc?>][quantity]" value="<?=$value['quantity']?>" /></td>
			<td><input type="text" class="return no-insert natural-no-validate" name="sales_data[<?=$inc?>][return]" value="<?=$value['return']?>" /></td>
			<td><input type="text" class="damage no-insert natural-no-validate" name="sales_data[<?=$inc?>][damage]" value="<?=$value['damage']?>" /></td>
			<td>
				<span class="sale"><?=$sale?></span>
				<input type="hidden" class="sale natural-no-validate" name="sales_data[<?=$inc?>][sale]" value="<?=$sale?>" />
			</td>
			<td>
				<span class="amount"><?=number_format($amount, 2)?></span>
				<input type="hidden" class="amount natural-no-validate" name="sales_data[<?=$inc?>][amount]" value="<?=$amount?>" />
			</td>
			<td><span class="remove"><i class="material-icons">close</i></span></td>
		</tr>
		<?php
		$inc++;
		$exist_data[] = $value['product_id'];
	}
}
if(isset($products) && $products && in_array($products, $product_ids) 
&& !in_array($products, $exist_data)
){
	$pos = array_search($products, $product_ids);
	$product_data = $products_list[$pos];
	?>
	<tr class="sales-item">
		<td>
			<?=ucfirst($product_data->name)?>
			<input type="hidden" class="product_id natural-no-validate" name="sales_data[<?=$inc?>][product_id]" value="<?=$product_data->id?>" />
		</td>
		<td>
			<?=number_format($product_data->{$price_type}, 2)?>
			<input type="hidden" class="price natural-no-validate" name="sales_data[<?=$inc?>][price]" value="<?=$product_data->{$price_type}?>" />
		</td>
		<td><input type="text" class="quantity no-insert natural-no-validate" name="sales_data[<?=$inc?>][quantity]" value="0" /></td>
		<td><input type="text" class="return no-insert natural-no-validate" name="sales_data[<?=$inc?>][return]" value="0" /></td>
		<td><input type="text" class="damage no-insert natural-no-validate" name="sales_data[<?=$inc?>][damage]" value="0" /></td>
		<td>
			<span class="sale">0</span>
			<input type="hidden" class="sale natural-no-validate" name="sales_data[<?=$inc?>][sale]" value="0" />
		</td>
		<td>
			<span class="amount">0</span>
			<input type="hidden" class="amount natural-no-validate" name="sales_data[<?=$inc?>][amount]" value="0" />
		</td>
		<td><span class="remove"><i class="material-icons">close</i></span></td>
	</tr>
	<?php
}
?>
<!-- <tr>
	<td colspan="8"><?php
		print_r($price_type);
		print_r($product_data->{$price_type});
	?></td>
</tr> -->
<tr class="no-result">
	<td colspan="8" style="text-align: center;">No items added.</td>
</tr>
