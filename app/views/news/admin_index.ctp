<div class="news index">
<h2><?php __('News');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Titolo', 'title');?></th>
	<th><?php echo $paginator->sort('Categoria', 'newscategory_id');?></th>
	<th><?php echo $paginator->sort('Attiva', 'active');?></th>
	<th><?php echo $paginator->sort('Data di pubblicazione', 'date_on');?></th>
	<th><?php echo $paginator->sort('Data di scadenza', 'date_off');?></th>
	<th class="actions" colspan="2"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($news as $news):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $news['News']['title']; ?>
		</td>
		<td>
			<?php echo $categories[$news['News']['newscategory_id']]; ?>
		</td>
		<td>
			<?php echo $this->Html->link($news['News']['active']?'si':'no', array('action'=>'toggle_active', $news['News']['id'])); ?>
		</td>
		<td>
			<?php echo date('d F Y', strtotime($news['News']['date_on'])); ?>
		</td>
		<td>
			<?php echo date('d F Y', strtotime($news['News']['date_off'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Modifica', true), array('action'=>'edit', $news['News']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Elimina', true), array('action'=>'delete', $news['News']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $news['News']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>


<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Nuova news', true), array('action'=>'add')); ?></li>
        <li><?php echo $this->Html->link(__('Gestisci le pagine', true), array('controller'=>'pages', 'action'=>'index')); ?></li>
		<li><?php echo $this->Html->link(__('Gestisci le categorie', true), array('controller'=>'newscategories', 'action'=>'index')); ?></li>
	</ul>
</div>