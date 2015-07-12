<?php
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

$categories = CategoryFactory::getCategories();
$hosts = HostFactory::getHosts();
?>
<h2>Cerca</h2>
<form method="post" action="learner/filter">
    <label for="name">Nome</label>
    <input name="name" id="name" type="text">
    <h3>Categoria</h3>
    <p>
        <?php
        foreach($categories as $category)  {
            ?>
            <input type="checkbox" name="category">
            <?= $category->getName(); ?>
            <br/>
            <?php
        }
        ?>  
    </p>
    <h3>Host</h3>
    <p>
        <?php
        foreach($hosts as $host)  {
            ?>
            <input type="checkbox" name="category">
            <?= $host->getName(); ?>
            <br/>
            <?php
        }
        ?>  
    </p>
    <button type="submit" name="cmd" value="filter">Cerca</button>
</form>
