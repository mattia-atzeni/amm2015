<?php

class ViewDescriptor {
    private $title;
    private $errorMessage;
    private $navigationBar;
    private $content;
    
    public function getTitle() {
        return $this->title;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function getNavigationBar() {
        return $this->navigationBar;
    }

    public function getContent() {
        return $this->content;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    public function setNavigationBar($navigationBar) {
        $this->navigationBar = $navigationBar;
    }

    public function setContent($content) {
        $this->content = $content;
    }

 
}
