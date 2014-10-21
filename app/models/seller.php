<?php

class Seller extends AppModel {

	var $name = 'Seller';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Hamper' => array(
			'className' => 'Hamper',
			'foreignKey' => 'seller_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'OrderedProduct' => array(
			'className' => 'OrderedProduct',
			'foreignKey' => 'seller_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'seller_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	var $hasAndBelongsToMany = array('User');
	var $actsAs = array('Containable');

//
//    function afterSave($created) {
//        if(isset($this->data['Seller']['User'])) {
//            $this->User->updateAll(array('seller_id' => 0), array('User.seller_id' => $this->id));
//            $this->User->updateAll(array('seller_id' => $this->id), array('User.id' => $this->data['Seller']['User']));
//        }
//        return parent::afterSave($created);
//    }

	function beforeFind($queryData) {
		if (Configure::read('ReferentUser.allowed_sellers')) {
			$allowed_sellers=Configure::read('ReferentUser.allowed_sellers');
			$forbidden_sellers=0;

			if (isset($queryData['conditions']['Seller.id'])) {
				$testCondition = $queryData['conditions']['Seller.id'];								
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
					$queryData['conditions']['Seller.id'] =  FALSE;								
			}
			else
			{
				// Aggiungo i fornitori di cui l'utente e' referente
				$queryData = array_merge_recursive($queryData,
						array('conditions' => array('Seller.id' => Configure::read('ReferentUser.allowed_sellers'))));
			}
			
			
		}
		return $queryData;
	}

	function getSellers($options = null) {

		$_options = array(
			'conditions' => array('active' => 1),
			'fields' => array('id', 'name'),
			'contain' => array());

		$options = am($_options, $options);

		$sellers = $this->find('all', $options);

		return $sellers;
	}

	function getUserEmails($id) {
		$users = $this->User->find('all', array(
				'conditions' => array('User.active' => 1),
				'fields' => array('email'),
				'recursive' => -1));
		$emails = Set::extract('/User/email', $users);
		return $emails;
	}

}

?>