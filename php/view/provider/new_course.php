<?php
    include_once "php/model/CategoryFactory.php";
    $categories = CategoryFactory::getCategories();
?>
<form method="post" action="provider">
    <label for="name">Nome del corso</label>
    <br/>
    <input id="name" type="text" name="name">
    <br/>
    <label for="link">Link al corso</label>
    <br/>
    <input type="text" id="link" name="link">
    <br/>
    <select name="category" id="category">
        <?php foreach ($categories as $category) { ?>
            <option value="<?= $category->getId()?>" ><?= $category->getName() ?></option>
        <?php } ?>
    </select>
    <br/>
    <button id="cancel_button" name="cmd" value="cancel">Annulla</button>
    <button id="save_course_button" type="submit" name="cmd" value="save_course">OK</button>
</form>

