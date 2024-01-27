<?php
class Person
{
    public $name;
    protected $gender;
    private $age;
    public function setAge($age){
        $this->age=$age;
        return $this;
    }
}
