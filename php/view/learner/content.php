<?php
    if ($vd->getSubpage() != null && $vd->getSubpage() != "home") {
        include $vd->getSubpage() . ".php";
    }
    else {   
        ?>
        <h2>Ciao, <?=$user->getFirstName()?>!</h2>
        <?php
            $courses = CourseFactory::getCoursesByLearnerId($user->getId());
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

        <form method="post" action="learner/browse">
            <button type="submit">Browse</button>
        </form>
        <?php
    }
?>

