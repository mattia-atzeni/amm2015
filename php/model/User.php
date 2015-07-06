<?php

class User {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $username;
    private $password;
   
    public function getId() {
        return $this->id;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setUsername($username) {
        if (!filter_var($username, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z]{5,}/')))) {
             return false;
         }
         $this->username = $username;
         return true;
    }

    public function setPassword($password) {
        $this->password = $password;
    }


}