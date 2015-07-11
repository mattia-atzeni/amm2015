<?php
include_once 'ViewDescriptor.php';
include_once 'php/model/HostFactory.php';
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
        <div id="page">
            <nav>
                <ul>
                    <li><a href="login" class="logo"><img src="images/mooc.png" alt="mooc"></a></li>
                    <?php
                    if ($vd->getNavigationBar() != null) {
                        $bar = $vd->getNavigationBar();
                        require "$bar";
                    }
                    ?>
                </ul>
            </nav>
            <br/>
            <div id="sidebar1">
                <?php $hosts = HostFactory::getHosts(5); ?>
                <ul class="hosts">
                    <li>
                        Link Esterni
                        <ul>
                            <?php
                            foreach ($hosts as $host) {
                                ?>
                                <li>
                                    <a href="<?=$host->getLink()?>" target="_blank">
                                       <?=$host->getName()?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div id ="sidebar2">
                
            </div>
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
            <div class="clear"></div>
            <footer>
                <p>
                    Progetto di Amministrazione di Sistema 2015<br/>
                    Autore: Mattia Atzeni - Matricola: 48958
                </p>
            </footer>
        </body>
    </div>
</html>

