<?php
	require 'PHP-Parser/lib/bootstrap.php';
	require 'MyNodeVisitor.php';
	require 'RefDetector.php';


	$parser = new PhpParser\Parser(new PhpParser\Lexer);
	$prettyPrinter = new PhpParser\PrettyPrinter\Standard;

	$traverser0     = new PhpParser\NodeTraverser; // Auxiliar para percorrer a AST
	$traverser1     = new PhpParser\NodeTraverser; // Auxiliar para percorrer a AST


	if (isset($argv[1]) and isset($argv[2])) {
    	$dirOldVersion = $argv[1];
    	$dirNewVersion = $argv[2];
	}
	else {

		if(isset($_GET['newV']) && isset($_GET['oldV'])){
    		$dirOldVersion = $_GET['oldV'];
    		$dirNewVersion = $_GET['newV'];
    	}
    	else{
    		print "Nenhum diretorio informado\n";
    		exit(0);
    	}
	}

	// TESTES DIR
	//$dirOldVersion = "test/v0/";
	//$dirNewVersion = "test/v1/";

	// add your visitor
	$myVisitor0 = new MyNodeVisitor;
	$traverser0->addVisitor($myVisitor0);


	$myVisitor1 = new MyNodeVisitor;
	$traverser1->addVisitor($myVisitor1);



	try {

		if ($dh = opendir($dirOldVersion)) {
        	while (($file = readdir($dh)) !== false) {
        		if ($file != '.' and $file != '..'){
            		//echo "filename: $file" . "\n";

					$code0 = file_get_contents($dirOldVersion.$file);
            		$stmts0 = $parser->parse($code0);
            		$stmts0 = $traverser0->traverse($stmts0);

            	}
        	}
        	closedir($dh);
    	}


		if ($dh = opendir($dirNewVersion)) {
        	while (($file = readdir($dh)) !== false) {
        		if ($file != '.' and $file != '..'){
            		//echo "filename: $file" . "\n";

					$code1 = file_get_contents($dirNewVersion.$file);
            		$stmts1 = $parser->parse($code1);
            		$stmts1 = $traverser1->traverse($stmts1);

            	}
        	}
        	closedir($dh);
    	}

	} catch (PhpParser\Error $e) {
	    echo 'Parse Error: ', $e->getMessage();
	}


	$Modelo = new RefDetector();

	$Modelo->set_oldVersion($myVisitor0->estrutura);
	$Modelo->set_newVersion($myVisitor1->estrutura);

	$Modelo->getRefactorings();
	$Modelo->printRefactorings();

?>