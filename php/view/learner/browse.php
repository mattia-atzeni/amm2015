<?php
    include_once 'php/model/CourseFactory.php';
    $courses = CourseFactory::getCourses();
?>
<h1>Catalogo</h1>
<ul class="courses">
    <?php
    foreach ($courses as $course) {
        ?>
        <li class="<?=$course->getHost()->getName()?>">
            <a class="course" href="<?=$course->getLink()?>" target="_blank">
                <h4 class="name"><?=$course->getName()?></h4>
                <p class="category">Categoria: <?=$course->getCategory()->getName()?></p>
            </a>
            <form action="learner" method="post">
                <input type="hidden" name="cmd" value="join">
                <input type="hidden" name="course_id" value="<?=$course->getId()?>">
                <button class="join-button" type="submit">Iscriviti</button>
            </form>
        </li>
        <?php
    }
    ?>
</ul>

