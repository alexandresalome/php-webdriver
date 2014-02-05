<?php
if (isset($_GET['create']) && $_GET['create'] == 'foo') {
    setcookie('foo', 'value of the foo cookie');
    header('Location: cookies.php');

    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cookies page</title>
    </head>
    <body>
        <h1>Cookies page</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php if (0 == count($_COOKIE)): ?>
                    <tr>
                        <td colspan="2">No cookie present</td>
                    </tr>
                <?php endif ?>
                <?php foreach ($_COOKIE as $name => $value): ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td data-cookie="<?php echo $name; ?>"><?php echo $value; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p><a href="cookies.php?create=foo">Create foo cookie</a>
    </body>
</html>
