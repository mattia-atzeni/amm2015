<?php
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

$categories = CategoryFactory::getCategories();
$hosts = HostFactory::getHosts();
?>
<h2>Cerca</h2>

<div class="error">
    <ul></ul>
</div>

<form id="filter-form" method="post">
    <label for="name">Nome</label>
    <input name="name" id="name" type="text">
    <br/>
    <div id="categories">
        <h3>Categoria</h3>
        <p>
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
    </div>
    <div id="hosts">
        <h3>Host</h3>
        <p>
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
    </div>
    <div class="clear"></div>
    <button id="filter" type="submit" name="cmd" value="filter">Cerca</button>
</form>
<p id='none'>Nessun corso trovato<p/>
<ul id="filtered-courses-list" class="courses" class="left"></ul>