<?php

	class RefDetector {

		private $old_structure; // estrutura que contém as classes da versão antiga
		private $new_structure; // estrutura que contém as classes da nova versão
		private $refactorings = array(); // array com os refactorings encontrados

		private $created_superclasses = array(); // superclasses e as classes que as extendem


		public function __constructor($old_structure){
			$this->old_structure = $old_structure;
		}

		public function set_oldVersion($old_structure){
			$this->old_structure = $old_structure;
		}

		public function set_newVersion($new_structure){
			$this->new_structure = $new_structure;
		}


		/* Analisa as duas estruturas e extrai os possíveis refactorings */
		public function getRefactorings(){

			foreach ($this->old_structure as $old){
				foreach ($this->new_structure as $new){

					if($old["name"] == $new["name"]){

						// Move Attribute
						$this->compareAttributes($old, $new);

						// Extract Superclass
						$this->compareSuperClasses($old, $new);

						// Move Operation
						$this->compareMethods($old, $new);

						//Outras Implementações ...
					}
				}
			}

			$this->includeExtractSuperClassRef();


		}

		// Inclui as superclasses encontradas na lista de refactorings
		// feito após associar a superclasse com as classes que a criaram
		public function includeExtractSuperClassRef(){

			foreach ($this->created_superclasses as $key => $classes){
				$str_ref = "Extract SuperClass ".$key." from classes [";

				foreach ($classes as $class_name){
					$str_ref .= $class_name.", ";
				}

				$str_ref = rtrim($str_ref, " ");
				$str_ref = rtrim($str_ref, ",");

				$str_ref .= "]";

				array_push($this->refactorings, $str_ref);
			}
		}


		// compara os atributos de duas classes para
		// determinar se foram alterados ou não
		public function compareAttributes($old, $new){
			foreach ($old["attributes"] as $old_att){

				$noKept = TRUE;
				foreach ($new["attributes"] as $new_att){
					if($new_att[1] == $old_att[1]){
						$noKept = FALSE;
					}
				}

				if($noKept){
					if(empty($new["extends"])){
						array_push($this->refactorings, "Attribute Removed: ".$old["name"]." ".$old_att[1]);
					}
					else{
						$superclass = $this->getClass($new["extends"], "NEW");
						if($superclass != NULL){
							if($this->checkAttribute($superclass["name"], $old_att[1])){
								array_push($this->refactorings, "Move Attribute: ".$old["name"]." ".$old_att[1]." to ".$superclass["name"]." ".$old_att[1]);
							}
						}						
					}
				}
			}
		}


		// Compara métodos de duas versões para 
		// verificar se ouve move para a superclasse
		public function compareMethods($old, $new){

			
			foreach ($old["methods"] as $old_meth){

				$noKept = TRUE;
				foreach ($new["methods"] as $new_meth){
					if($new_meth == $old_meth){
						$noKept = FALSE;
					}
				}

				if($noKept){					
					if(empty($new["extends"])){
						array_push($this->refactorings, "Method Removed: ".$old["name"]." ".$old_meth);
					}
					else{			
						$superclass = $this->getClass($new["extends"], "NEW");
						if($superclass != NULL){
							$match = $this->checkMethod($superclass["name"], $old_meth);
							if($match != FALSE){
								array_push($this->refactorings, "Move Operation: ".$old["name"]." ".$old_meth." to ".$superclass["name"]." ".$match);
							}
						}
					}
				}
			}
		}



		public function printRefactorings(){
			print "\n"."REFACTORINGS: "."\n";
			foreach ($this->refactorings as $ref){
				print $ref."\n";
			}
			print "\n";
		}


		// verifica se existe a classe na estrutura indicada (NEW/OLD)
		public function getClass($name, $structure = "OLD"){
			$estrutura = (strtoupper($structure) == "NEW") ? $this->new_structure : $this->old_structure;
			foreach($estrutura as $class){
				if($class["name"] == $name){
					return $class;
				}
			}
			return NULL;
		}

		// verifica se o atributo pertence a classe
		public function checkAttribute($class_name, $attribute_name){
			$class = $this->getClass($class_name, "NEW");
			foreach ($class["attributes"] as $att){
				if($att[1] == $attribute_name){
					return TRUE;
				}
			}
			return FALSE;
		}

		// verifica se o metodo pertence a classe
		public function checkMethod($class_name, $method_name){
			$class = $this->getClass($class_name, "NEW");
			foreach ($class["methods"] as $meth){
				
				if($meth == $method_name){					
					return $meth;
				}
			}
			return FALSE;
		}		


		// Encontra as superclasses criadas
		public function compareSuperClasses($old_class, $new_class){

			if(empty($old_class["extends"]) and !(empty($new_class["extends"]))){

				$new_superclass = (is_object($new_class["extends"])) ? (string)$new_class["extends"] : $new_class["extends"];
				if(array_key_exists($new_superclass, $this->created_superclasses)){
					array_push($this->created_superclasses["$new_superclass"], $new_class["name"]);
				}
				else{
					$this->created_superclasses["$new_superclass"] = array($new_class["name"]);
				}
			}

		}



	}


?>