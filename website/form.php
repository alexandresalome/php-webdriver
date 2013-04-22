<!DOCTYPE html>
<html>
    <head>
        <title>Sample website</title>
    </head>
    <body>
        <form action="form.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="text">A text field: </label>
                <input type="text" id="text" name="text" />
            </p>
            <p>
                <label for="file">File to upload: </label>
                <input type="file" id="file" name="file" />
            </p>
            <p>
                <button type="submit" id="submit">Submit</button>
            </p>
        </form>
        <?php if (count($_POST)): ?>
            <hr />
            <p>Text field: <span id="post-text"><?php echo isset($_POST['text']) ? htmlentities($_POST['text'], ENT_QUOTES, 'UTF-8') : ''; ?></span></p>
            <?php if (isset($_FILES['file'])): ?>
                <p>File uploaded: <span id="post-size"><?php echo htmlentities($_FILES['file']['size'], ENT_QUOTES, 'UTF-8'); ?></span> bytes</p>
            <?php else: ?>
                <p>No file uploaded</p>
            <?php endif ?>
        <?php endif ?>
    </body>
</html>
