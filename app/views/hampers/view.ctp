<?php

echo $this->Html->css('jquery.tooltip', null, array('inline' => false));
echo $this->Javascript->link('jquery.tooltip', false);

$this->Layout->blockStart('js_on_load');

echo <<<JS

$('.product .options form', '.products').hide();
$('.product .options h4', '.products').css({cursor: 'pointer'}).click(function(){
    $(this).next('form').slideToggle();
});


$('.product a').tooltip({
    track: true,
    delay: 0,	
    showURL: false,
    showBody: ' - ',
    fade: 250
});


JS;

$this->Layout->blockEnd();
?>

<div class="hampers view">
    <h2><?php __('Paniere di'); ?> <?php echo $hamper['Hamper']['name']; ?></h2>

    <div class="report">
		<?php
		__('Questo paniere è prodotto da ');
		echo $this->Html->link($hamper['Seller']['name'], array('controller' => 'sellers', 'action' => 'view', $hamper['Seller']['id']));

		echo '<br/>';
		__('Puoi acquistare questi prodotti da ');
		echo digi_date($hamper['Hamper']['start_date']);
		__(' fino a ');
		echo digi_date($hamper['Hamper']['end_date']);

		if (!date_is_empty($hamper['Hamper']['checkout_date'])) {
			echo '<br/>';
			__('Questo acquisto deve essere pagato entro ');
			echo digi_date($hamper['Hamper']['checkout_date']);
		}
		?>
        <br/>
        <strong>
			<?php
			if (!empty($hamper['Hamper']['delivery_position'])) {
				__('Ritira la merce presso ');
				echo $hamper['Hamper']['delivery_position'];
			}
			if (!date_is_empty($hamper['Hamper']['delivery_date_on'])) {
				echo ' ';
				if (date("d,m,Y", strtotime($hamper['Hamper']['delivery_date_on'])) == date("d,m,Y", strtotime($hamper['Hamper']['delivery_date_off']))) {
					echo date_it("D d M Y", strtotime($hamper['Hamper']['delivery_date_on']));
					__(' dalle ');
					echo date("H:i", strtotime($hamper['Hamper']['delivery_date_on']));
					__(' alle ');
					echo date("H:i", strtotime($hamper['Hamper']['delivery_date_off']));
				} else {
					__(' da ');
					echo digi_date($hamper['Hamper']['delivery_date_on']);
					__(' a ');
					echo digi_date($hamper['Hamper']['delivery_date_off']);
				}
			}

			$_notes = strip_tags($hamper['Hamper']['notes']);
			if (!empty($_notes)) {
				echo $this->Html->div('attention', $hamper['Hamper']['notes']);
			}
			?>
        </strong>
    </div>

    <div class="related products">
        <h2><?php __('Prodotti in questo paniere'); ?></h2>
		<?php if (!empty($hamper['Product'])): ?>
		<?php
				$category = '';
				foreach ($hamper['Product'] as $product):
					if ($product['ProductCategory']['name'] != $category) {
						$category = $product['ProductCategory']['name'];
						echo $this->Html->tag('h3', $product['ProductCategory']['name']);
					}
		?>
		<?php
					$title = "";
					if ($product['text']) {
						$title .= "<strong>Descrizione: </strong>" . $this->Text->truncate(strip_tags($product['text'])) . "<br />";
					}
					if ($product['packing']) {
						$title .= "<strong>Imballaggio: </strong>" . $this->Text->truncate(strip_tags($product['packing']));
					}
					if (!$title) {
						$title = $product['name'];
					}
		?>
					<div class="product"  >

						<a title="<?php echo $title ?>" href="<?php echo $this->Html->url(array('controller' => 'products', 'action' => 'view', $product['id'])); ?>">
				<?php
					if ($product['image'])
						echo $this->Image->resize('/documents/image/product/' . $product['image'], '160', '120');
				?>

					<div class="name"><?php echo $product['name']; ?></div>
				</a>

			<div class="detail">
				<?php echo $this->Html->link(__('Scheda prodotto', true), array('controller' => 'products', 'action' => 'view', $product['id'])); ?>
			</div>

			<?php if (!empty($product['weight'])): ?>
						<div class="weight">
				<?php
						__('Peso: ');
						echo $product['weight'];
				?>
					</div>
			<?php endif; ?>

			<?php if (!empty($product['value'])): ?>
							<div class="value">
				<?php
							__('Prezzo unitario: ');
							echo $this->Number->currency($product['value'], 'EUR');
				?>
						</div>
			<?php endif; ?>



							<div class="options">
								<h4><?php __('Ordina'); ?></h4>
				<?php
							echo $this->Form->create('OrderedProduct', array('action' => 'add'));
							echo $this->Form->hidden('product_id', array('value' => $product['id']));
							echo $this->Form->hidden('hamper_id', array('value' => $hamper['Hamper']['id']));
							echo $this->Form->hidden('seller_id', array('value' => $hamper['Seller']['id']));
							$selectOptions = array();
							for ($i = 1; $i < 51; $i++) {
								$value = (string) ($i * $product['number']);
								$selectOptions[$value] = $value;
							}

							echo $this->Form->input('quantity', array('options' => $selectOptions, 'label' => ucfirst($product['units'])));
							if ($product['option_list_1']) {
								$options = array();
								foreach (explode(';', $product['option_list_1']) as $opt) {
									$options[$opt] = $opt;
								}

								echo $this->Form->input('option_1', array('type' => 'select', 'options' => $options, 'label' => __($product['option_1'], true)));
							}
							if ($product['option_list_2']) {
								$options = array();
								foreach (explode(';', $product['option_list_2']) as $opt) {
									$options[$opt] = $opt;
								}
								echo $this->Form->input('option_2', array('type' => 'select', 'options' => $options, 'label' => __($product['option_2'], true)));
							}
							if ($product['show_note'] == 1) {
								echo $this->Form->input('note', array('type' => 'text', 'label' => __('Note', true)));
							}
							echo $this->Form->end('Acquista');
				?>
						</div>

					</div>
		<?php endforeach; ?>
		<?php endif; ?>
						</div>

						<div class="clear"></div>
	<?php
							/*
							 * COMMENTI
							 */
//visualizzo i commenti solo per gli utenti registrati
							if ($this->Session->read('Auth.User.id')) {

								//elenco dei commenti
								echo $this->Html->tag('h3', __('Commenti', true));
								echo $this->UserComment->view($comments, false , true);


								//form di inserimento commenti
								echo $this->UserComment->add('Hamper', $hamper['Hamper']['id']);
							}
	?>

						</div>

						<div class="actions">
							<ul>
								<li><?php echo $this->Html->link(__('<< Ritorna ai panieri', true), array('action' => 'index')); ?></li>
							</ul>

<?php echo $this->element('user_order', array('userOrder' => $userOrder)); ?>

							<ul>
								<li><?php echo $this->Html->link(__('Vai alla tua pagina ordini', true), array('controller' => 'ordered_products', 'action' => 'index')); ?></li>
								<li><?php echo $this->Html->link(__('Mandami un promemoria', true), array('controller' => 'ordered_products', 'action' => 'send_me_orders_email')); ?></li>
	</ul>
</div>