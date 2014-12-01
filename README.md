ManutencaoEvolucao
==================

Projeto Final da Disciplina Manutenção e Evolução de Software - 2014/2

Ferramenta para detecção de refactorings em aplicações escritas em PHP.
O programa pode ser executado através do seguinte comando, dentro do diretório no qual se encontra, com o conjunto de teste disponibilizado:

  php main.php test/vo/ test/v1/

A saída esperada para essa execução é:

REFACTORINGS: 
Move Operation: Chicken layEgg to Bird layEgg
Move Attribute: Labrador age to Dog age
Move Operation: Labrador getAge to Dog getAge
Move Attribute: Poodle age to Dog age
Move Operation: Poodle getAge to Dog getAge
Move Operation: Duck layEgg to Bird layEgg
Extract SuperClass Bird from classes [Chicken, Duck]


Este código faz uso da biblioteca PHP-Parser disponível em: https://github.com/nikic/PHP-Parser/tree/master/doc
