<?php
include_once 'php/model/CourseFactory.php';

if ($vd->getSubpage() != null && $vd->getSubpage() != "home"){
    include $vd->getSubpage() . ".php";
}
else {   
    ?>
    <h2>Ciao, <?=$user->getFirstName()?>!</h2>
    <h3>I tuoi corsi</h3>
    <?php
        $courses = CourseFactory::getCoursesByOwnerId($user->getId());
        require 'php/view/coursesList.php';
    ?>

    <form action="provider/new_course" method="post">
        <button type="submit">Nuovo Corso</button>
    </form>
    <?php
}
?>

