<!DOCTYPE html>
<html>
    <head>
        <title>Sample website</title>
    </head>
    <body>
        <?php foreach (array('$_GET' => $_GET, '$_POST' => $_POST) as $name => $vals): ?>
            <h1><?php echo $name; ?></h1>
            <ul>
                <?php foreach ($vals as $key => $val): ?>
                    <li><strong><?php echo $name; ?>['<?php echo $key; ?>']</strong> = <?php echo $val; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </body>
</html>
