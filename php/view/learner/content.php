<?php
    include_once 'php/model/CourseFactory.php';

    if ($vd->getSubpage() != null && $vd->getSubpage() != "home") {
        include $vd->getSubpage() . ".php";
    }
    else {   
        ?>
        <h2>Ciao, <?=$user->getFirstName()?>!</h2>
        <?php
            $courses = CourseFactory::getCoursesByLearnerId($user->getId());
            require 'php/view/coursesList.php';
        ?>
        <form method="post" action="learner/catalog">
            <button type="submit">Catalogo</button>
        </form>
        <?php
    }
?>

