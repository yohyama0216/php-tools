<?php

namespace Test\Numbers;

class User{

    protected $name;

    public function __construct($name){

        $this->name = $name;

    }

    public function get_user_name(){

        return $this->name;

    }
}