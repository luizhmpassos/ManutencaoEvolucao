	<?php 

		//echo 'Hi ', hi\\getTarget();

		class Teste1 {

			public $id = 0;
			private $nome = "Luiz";

			public function get_id(){
				return $this->id;
			}
		}

		class Teste2 extends Test {

			public $id = 0;
			private $nome = "Laura";

			public	function get_id(){
					return $this->id;				
			}

			public function get_senha(){
				return $this->nome;
			}
		}