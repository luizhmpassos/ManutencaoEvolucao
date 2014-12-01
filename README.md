ManutencaoEvolucao
==================

Projeto Final da Disciplina Manutenção e Evolução de Software - 2014/2<br/>

Ferramenta para detecção de refactorings em aplicações escritas em PHP.<br/>
O programa pode ser executado através do seguinte comando, dentro do diretório no qual se encontra, com o conjunto de teste disponibilizado:

  php main.php test/vo/ test/v1/<br/>

A saída esperada para essa execução é:<br/>

REFACTORINGS: <br/>
  Move Operation: Chicken layEgg to Bird layEgg<br />
  Move Attribute: Labrador age to Dog age<br />
  Move Operation: Labrador getAge to Dog getAge<br />
  Move Attribute: Poodle age to Dog age<br/>
  Move Operation: Poodle getAge to Dog getAge<br/>
  Move Operation: Duck layEgg to Bird layEgg<br/>
  Extract SuperClass Bird from classes [Chicken, Duck]<br/>
<br/>
Este código faz uso da biblioteca PHP-Parser disponível em: https://github.com/nikic/PHP-Parser/tree/master/doc
