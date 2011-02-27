<?php
 Shop::register('shop.css');
 if(!isset($products)) {
$products = array();
$products = json_decode(Yii::app()->user->getState('cart'), true);
} ?>
<h2> <?php echo Shop::t('Shopping cart'); ?> </h2>

<?php
if($products) {
	$price_total = 0;

	echo '<table class="shopping_cart">';
	printf('<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>',
			Shop::t('Amount'),
			Shop::t('Product'),
			Shop::t('Variation'),
			Shop::t('Price Single'),
			Shop::t('Price Total'),
			Shop::t('Actions')
);

	foreach($products as $position => $product) {
		if(@$model = Products::model()->findByPk($product['product_id'])) {
			$price = $model->price;

			$variations = '';
			if(isset($product['Variations'])) {
				foreach($product['Variations'] as $specification => $variation) {
					$specification = ProductSpecification::model()->findByPk($specification);
					if($specification->is_user_input)
						$variation = $variation[0];
					else
						$variation = ProductVariation::model()->findByPk($variation);

					$variations .= $specification . ': ' . $variation . '<br />';
					@$price += $variation->price_adjustion;
				}
			}

			printf('<tr><td>%s</td><th>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
					$product['amount'],
					$model->title,
					$variations,
					$price,
					$price * $product['amount'],
					CHtml::link(Shop::t('Remove'), array('//shop/shoppingCart/delete', 'id' => $position), array('confirm' => Shop::t('Are you sure?')))
);
		$price_total += $price * $product['amount'];
		}
	}
	echo '</table>';

	echo Shop::t('Price total: {total}', array('{total}' => $price_total));
} else echo Shop::t('Your shopping cart is empty'); ?>

<hr />

<?php if(Yii::app()->controller->id != 'order') {
 echo CHtml::link(Shop::t('Buy additional Products'), array(
			'//shop/products')); ?>
&nbsp;
<?php echo CHtml::link(Shop::t('Buy this products'), array(
			'//shop/order/create')); 
}?>
