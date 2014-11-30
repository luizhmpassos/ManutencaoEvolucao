<?php
//package org.animals;
// namespace org.animals;

class Dog {

	private $age = 0;

	public function getAge() {
		return $this->age;
	}
	
	public function bark() {
		print "ruff"."\n";
		print "ruff"."\n";
		$this.takeABreath();
		print "ruff"."\n";
		print "ruff"."\n";
		print "ruff"."\n";
	}

	public function takeABreath() {
		print "..."."\n";
	}

}


?>