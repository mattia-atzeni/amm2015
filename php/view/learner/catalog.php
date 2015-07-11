<?php
    include_once 'php/model/CourseFactory.php';
    $courses = CourseFactory::getCourses();
?>
<h1>Catalogo</h1>
<?php
    require 'php/view/coursesList.php';


