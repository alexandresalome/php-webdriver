<!DOCTYPE html>
<html>
    <head>
        <title>Sample website</title>
    </head>
    <body>
        <!-- This comment is only viewable with source code -->
        <h1>Welcome to sample website</h1>
        <ul>
            <li><a href="rand.php">A link to a random hash</a></li>
            <li><a href="other.php">Another page</a></li>
            <li><a href="form.php">Page to test form stuff</a></li>
            <li><a href="cookies.php">Cookies page</a></li>
            <li><a href="alert.php">Alerts page</a></li>
            <li><a href="mouse.php">Mouse tests</a></li>
            <li><a href="request.php">What is your request?</a></li>
            <li><a href="tree.php">A tree with lot of informations, for node processing</a></li>
            <li><a href="index.php" onclick="window.open('index.php', '_blank', 'modal=yes'); return false;">Pop-up</a></li>
        </ul>
        <h2 id="hidden-element" style="display: none;">Hidden element</h2>
        <div id="danger-zone">
            <h2>DANGER ZONE</h2>
            <p><?php echo 'You are on page'.(isset($_GET['page']) ? $_GET['page'] : 1); ?></p>
            <div id="pagination">
                <p><a href="?page=1">page 1</a></p>
                <p><a href="?page=2">page 2</a></p>
                <p><a href="?page=3">page 3</a></p>
            </div>
        </div>
    </body>
</html>
