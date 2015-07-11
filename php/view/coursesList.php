<ul class="courses">
    <?php
    foreach ($courses as $course) {
        ?>
        <li class="<?=$course->getHost()->getName()?>">
            <a class="course" href="<?=$course->getLink()?>" target="_blank">
                <h4 class="name"><?=$course->getName()?></h4>
                <p class="category">Categoria: <?=$course->getCategory()->getName()?></p>
            </a>
            <?php
                switch ($vd->getSubpage()) {
                    case "catalog":
                        ?>
                        <form action="learner" method="post">
                            <input type="hidden" name="cmd" value="join">
                            <input type="hidden" name="course_id" value="<?=$course->getId()?>">
                            <button class="join-button" type="submit">Iscriviti</button>
                        </form>
                        <?php
                        break;
                }
            ?>
        </li>
        <?php
    }
    ?>
</ul>