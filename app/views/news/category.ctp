<div class="news-list index">
    <h2><?php __('News');?></h2>
    <p>
        <?php
        echo $paginator->counter(array(
        'format' => __('Pagina %page% di %pages%, riga da %start% a %end% di %count%', true)
        ));
        $paginator->options(array('url' => $this->passedArgs));
        ?></p>
    <div class="paging">
        <?php echo $paginator->prev('<< '.__('precedente', true), array(), null, array('class'=>'disabled'));?>
        | 	<?php echo $paginator->numbers();?>
        <?php echo $paginator->next(__('successiva', true).' >>', array(), null, array('class'=>'disabled'));?>
    </div>
    <?php
    foreach ($news as $one_news):
        ?>
    <div class="news">
        <div class="news-category"><?php echo $one_news['Newscategory']['name']; ?></div>
        <div class="news-date"><?php echo digi_date($one_news['News']['date_on']); ?></div>
        <h2><?php echo $this->Html->link($one_news['News']['title'], array('action'=>'view', $one_news['News']['id'])); ?></h2>
        <div class="summary"><?php echo $one_news['News']['summary']; ?>
            <span class="news-more">
                    <?php echo $html->link(__('Leggi tutto…', true), array('action'=>'view', $one_news['News']['id'])); ?>
            </span>
        </div>
    </div>
    <?php endforeach; ?>
    
</div>