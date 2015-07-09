<?php
include_once 'ViewDescriptor.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php $vd->getTitle() ?></title>
        <meta charset="utf-8"/>
        <base href="<?= Settings::getApplicationPath() ?>"/>
        <link rel="stylesheet"  type="text/css"  href="css/style.css">
    </head>

    <body>
        <nav>
            <ul>
                <li><a href="" class="logo"><img src="images/mooc.png" alt="mooc"></a></li>
                <?php
                if ($vd->getNavigationBar() != null) {
                    $bar = $vd->getNavigationBar();
                    require "$bar";
                }
                ?>
            </ul>
        </nav>
        <br/>
        <div id="content">
            <?php
            if (count($vd->getErrorMessages()) != 0) {
                ?>
                <div class="error">
                    <div>
                        <ul>
                        <?php
                        $errors = $vd->getErrorMessages();
                        foreach ($errors as $error) {
                            ?>
                            <li><?=$error?></li>
                            <?php
                        }
                        ?>
                        </ul>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            $content = $vd->getContent();
            if (isset($content)) {
                require "$content";
            }
            ?>
        </div>
        <footer>
            <p>
                Progetto di Amministrazione di Sistema 2015<br/>
                Autore: Mattia Atzeni - Matricola: 48958
            </p>
        </footer>
    </body>
</html>

