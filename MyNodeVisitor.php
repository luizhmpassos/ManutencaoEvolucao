<?php
	/*
        Implementa o iterador dos elementos da AST criada pelo Parser
        Adiciona uma estrutura para armazenar informações importantes
        sobre as classes
    */

	use PhpParser\Node;

	class MyNodeVisitor extends PhpParser\NodeVisitorAbstract {

		public $classes = array();
        //public $atributos = array();
        //public $metodos = array();


        public $estrutura = array();
        /*  Estrutura de dados para armazenamento das classes extraidas do arquivo/conjunto_de_arquivos fonte analisados

            [
                indice : 
                    nome_classe : string
                    extends     : nome_classe_pai
                    //implements  : [nome_interface1, ...]
                    atributos   : [[tipo_inferido, nome]. ...]
                    metodos     : [nome, ...]
            ]
            ...

        */

        private function addClass($nome){

            $aux = array(            
                "name" => $nome,
                "extends" => NULL,
                //"implements" => array(),
                "attributes" => array(),
                "methods" => array()
            );

            array_push($this->estrutura, $aux);
        }


    	public function leaveNode(Node $node) {

        	if ($node instanceof Node\Scalar\String) {
        		//array_push($this->classes, $node->value);
            	$node->value = 'foo_bar';
        	}


        	if ($node instanceof Node\Stmt\Class_) {

        		array_push($this->classes, $node->name);

                $class = array();
                $class["name"] = $node->name;
                $class["extends"] = $node->extends;
                $class["attributes"] = array();
                $class["methods"] = array();               


                foreach ($node->stmts as $stmt){

                    // Extrai os atributos da classe
                    if($stmt instanceof Node\Stmt\Property){
                        foreach ($stmt->props as $property){
                            array_push($class["attributes"], array(get_class($property->default), $property->name));
                        }
                    }

                    // Extrai os metodos da classe
                    if($stmt instanceof Node\Stmt\ClassMethod){
                        array_push($class["methods"], $stmt->name);
                    }

                }

                array_push($this->estrutura, $class);
                
        	}



    	}
	}