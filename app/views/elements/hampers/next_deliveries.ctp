<?php
if(isset($nextDeliveries)):
	echo "<h2>Le prossime consegne</h2>";
	if(!empty($nextDeliveries)):
?>	
	<?php
	$i=0;
	foreach ($nextDeliveries as $delivery) {
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' alt';
		}

		if(isset($delivery['Hamper'])) {
			//$author = $this->Html->div('comment-author', $comment['User']['fullname']);
			$title = $this->Html->div('comment-date', $delivery['Hamper']['name'] );
			
		} else {
			$title = '';
		}
		/*$date = $this->Html->div('comment-date', $this->Time->relativeTime($comment['Comment']['created']));
		$content = $this->Html->div('comment-text', $this->Text->truncate(strip_tags($comment['Comment']['text'])));
		$more = $this->Html->link(__('Leggi', true), '/' . $comment['Comment']['url'], array('class' => 'more'));
		echo $this->Html->div('comment'.$class, $author.$date.$content.$more);*/
		$content = $this->Html->div('comment-text', $this->Text->truncate(strip_tags($delivery['Hamper']['name']))."<br/><br/>");
		//$date = $this->Html->div('comment-date', $this->Time->relativeTime($delivery['Hamper']['delivery_date_on']));
		$deliveDay =  $this->Time->format('d M',$delivery['Hamper']['delivery_date_on']);
		$starTime = $this->Time->format('H:i',$delivery['Hamper']['delivery_date_on']);
		$endTime =  $this->Time->format('H:i',$delivery['Hamper']['delivery_date_off']);
		$date = $this->Html->div('comment-author', $deliveDay.' dalle '.$starTime.' alle '.$endTime);
		//echo $this->Html->div('comment'.$class, $date.$content." ".$title);
		echo $this->Html->div('comment'.$class, $date.$content);
	}
	?>
<?php 
	else:
		echo $this->Html->div('comment', "Nessuna consegna questa settimana.");
	endif;
endif;
?>
