<?php
class Product extends AppModel {
    var $name = 'Product';
    var $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'ProductCategory' => array(
                'className' => 'ProductCategory',
                'foreignKey' => 'product_category_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        ),
        'Seller' => array(
                'className' => 'Seller',
                'foreignKey' => 'seller_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        )
    );

    var $hasMany = array('OrderedProduct');

    var $hasAndBelongsToMany = array(
        'Hamper' => array(
                'className' => 'Hamper',
                'joinTable' => 'hampers_products',
                'foreignKey' => 'product_id',
                'associationForeignKey' => 'hamper_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'deleteQuery' => '',
                'insertQuery' => ''
        )
    );

    var $validate = array(
        'name' => array('rule' => 'notEmpty', 'on' => 'create'),
        'value' => array('rule' => 'notEmpty', 'on' => 'create'),
		'number' => array('numeric')
    );

    var $actsAs = array('Containable', 'Commentable' => array('forumName' => 'Prodotti'), 'MultipleUpload' =>
                            array(
                                'image' => array(
                                    'field' => 'image',
                                    'dir' => "{WWW_ROOT}documents{DS}image{DS}product", //markers: '{APP}', '{DS}', '{IMAGES}', '{WWW_ROOT}', '{FILES}'
                                    'deleteMainFile' => false,
									'randomFilenames' => true,
                                    'thumbsizes' => array()
                                )
                            )
                        );

	
	function beforeFind($queryData) {
		if (Configure::read('ReferentUser.allowed_sellers')) {
			$allowed_sellers=Configure::read('ReferentUser.allowed_sellers');
			$forbidden_sellers=0;
			
			if (isset($queryData['conditions']['Product.seller_id'])) {		
				$testCondition = $queryData['conditions']['Product.seller_id'];		
				// devo vedere se e' un valore singolo e cercarlo
				// altrimenti se e' un array devo scorrerlo e cercarli tutti
				if (is_array($testCondition))
				{
					$arrayLen = count($testCondition);
					for($x=0;$x<$arrayLen;$x++) {
						if (!in_array($testCondition[$x],$allowed_sellers))
  						 { $forbidden_sellers += 1; }
 					}	
				}
				else				
					if (!in_array($testCondition,$allowed_sellers))
					{
						$forbidden_sellers=1;
					}
				if ($forbidden_sellers <> 0)
					// Situazione anomala, devo invalidare   						
					$queryData['conditions']['Product.seller_id'] =  FALSE;								
			}
			else
			{
				// Aggiungo i fornitori di cui l'utente e' referente
				$queryData = array_merge_recursive($queryData,
						array('conditions' => array('Product.seller_id' => Configure::read('ReferentUser.allowed_sellers'))));
			}
		}
		return $queryData;
	}

    function beforeSave() {
        if(isset($this->data['Product']['number'])) {
            $number = str_replace(',', '.', $this->data['Product']['number']);
            $this->data['Product']['number'] = $number;
        }

        if(isset($this->data['Product']['value'])) {
            $value = str_replace(',', '.', $this->data['Product']['value']);
            $this->data['Product']['value'] = $value;
        }
        return parent::beforeSave();
    }
    
    function getAllFromSellerByCategory($seller_id) {
        $products = $this->find('all', array(
            'conditions' => array('seller_id' => $seller_id),
            'fields' => array('id', 'name', 'image'),
            'contain' => array(
                'ProductCategory' => array(
                    'order' => 'ProductCategory.lft asc',
                    'fields' => array('id', 'name', 'parent_id', 'lft', 'rght')
                )
            )
        ));
        //formatto l'elenco in modo utile per il frontend
        $productCategories = array();
        foreach($products as $product) {
            $productCategories[$product['ProductCategory']['id']]['ProductCategory'] = $product['ProductCategory'];
            $productCategories[$product['ProductCategory']['id']]['Product'][] = $product['Product'];
        }
        return $productCategories;
    }

}
?>