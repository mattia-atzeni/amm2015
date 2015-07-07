<form method="post" action="">
    <label for="name">Nome del corso</label>
    <input id="name" type="text" name="name">
    <br/>
    <label for="link">Link al corso</label>
    <input type="text" id="link" name="link">
    <br/>
    <select name="category" id="category">
        <?php foreach ($categories as $category) { ?>
            <option value="<?= $category->getId()?>" ><?= $category->getName() ?></option>
        <?php } ?>
    </select>
    <input type="hidden" name="cmd" value="save_course">
    <input type="submit" value="OK">
</form>

