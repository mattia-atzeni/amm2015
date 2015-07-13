<?php
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

$categories = CategoryFactory::getCategories();
$hosts = HostFactory::getHosts();
?>
<h2>Cerca</h2>
<form method="post">
    <label for="name">Nome</label>
    <input name="name" id="name" type="text">
    <h3>Categoria</h3>
    <p id="categories">
        <?php
        foreach($categories as $category)  {
            ?>
            <input type="checkbox" name="categories" value="<?=$category->getId()?>">
            <?= $category->getName(); ?>
            <br/>
            <?php
        }
        ?>  
    </p>
    <h3>Host</h3>
    <p id="hosts">
        <?php
        foreach($hosts as $host)  {
            ?>
            <input type="checkbox" name="hosts" value="<?=$host->getId()?>">
            <?= $host->getName(); ?>
            <br/>
            <?php
        }
        ?>  
    </p>
    <ul id="courses-list" class="courses"></ul>
    <button id="filter" type="submit" name="cmd" value="filter">Cerca</button>
</form>
