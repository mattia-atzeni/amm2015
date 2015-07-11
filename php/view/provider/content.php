<?php

    if ($vd->getSubpage() != null){
        include $vd->getSubpage() . ".php";
    }
    else {   
        ?>
        <h2>Ciao, <?=$user->getFirstName()?>!</h2>
        <h3>I tuoi corsi</h3>
        <?php
            $courses = CourseFactory::getCoursesByOwnerId($user->getId());
            ?>
            <ul class="courses">
                <?php
                foreach ($courses as $course) {
                    ?>
                    <li class="<?=$course->getHost()->getName()?>">
                        <a class="course" href="<?=$course->getLink()?>" target="_blank">
                            <h4 class="name"><?=$course->getName()?></h4>
                            <p class="category">Categoria: <?=$course->getCategory()->getName()?></p>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>

        <form action="provider/new_course" method="post">
            <button type="submit">Nuovo Corso</button>
        </form>
        <?php
    }
?>

