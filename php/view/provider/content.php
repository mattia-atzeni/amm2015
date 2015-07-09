<?php

    if ($vd->getSubpage() != null){
        include $vd->getSubpage() . ".php";
    }
    else {   
        ?>
        <h2>Ciao, <?=$user->getFirstName()?>!</h2>
        <h3>I tuoi corsi</h3>
        <?php
            $courses = CourseFactory::getCoursesByOwner($user->getId());
            ?>
            <ul>
                <?php
                foreach ($courses as $course) {
                    ?>
                    <li><?=$course->getName()?></li><?php
                }
                ?>
            </ul>

        <form action="provider/new_course" method="post">
            <button type="submit">Nuovo Corso</button>
        </form>
        <?php
    }
?>

