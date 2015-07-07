<?php

class Course {
    private $id;
    private $name;
    private $link;
    private $category;
    private $platform;
    private $owner;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        return $this->link;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getPlatform() {
        return $this->platform;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
        return true;
    }

    public function setName($name) {
        $this->name = $name;
        return true;
    }

    public function setLink($link) {
        $valid = filter_var($link, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE);
        if (isset($valid)) {
            $this->link = $link;
            return true;
        } else {
            $temp = "http://" . $link;
            $valid = filter_var($temp, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE);
            if (isset($valid)) {
                $this->link = $link;
                return true;
            }
            return false;
        }
    }

    public function setCategory(Category $category) {
        $this->category = $category;
        return true;
    }

    public function setPlatform($platform) {
        $this->platform = $platform;
        return true;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
        return true;
    }
}