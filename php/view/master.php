<?php
include_once 'ViewDescriptor.php';
include_once 'php/model/HostFactory.php';

if (!$vd->isJson()) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title><?php $vd->getTitle() ?></title>
            <base href="<?= Settings::getApplicationPath() ?>"/>
            <link rel="stylesheet"  type="text/css"  href="css/style.css">
            <?php
            foreach ($vd->getScripts() as $script) {
                ?>
                <script type="text/javascript" src="<?=$script?>"></script>
                <?php
            }
            ?>
        </head>

        <body>
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
            <div id="page">
                <div id="sidebar">
                    <?php $hosts = HostFactory::getHosts(); ?>
                        <ul>
                            <?php
                            foreach ($hosts as $host) {
                                ?>
                                <li>
                                    <a href="<?=$host->getLink()?>" target="_blank" class="<?=$host->getName()?> host">
                                        <p><?=$host->getName()?></p>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                </div>
                <div id="content">
                    <?php
                    if (count($vd->getErrorMessages()) != 0) {
                        ?>
                        <div class="error">
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
            </div>
        </body>
    </html>
    <?php
    
} else {
    
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    
    $content = $vd->getContent();
    require "$content";
    
}
?>

