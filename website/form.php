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
                <label for="checkbox">A checkbox: </label>
                <input type="checkbox" id="checkbox" name="checkbox" value="1" />
            </p>
            <p>
                <label for="radio">A radio: </label>
                <input type="radio" id="radio_1" value="1" name="radio" /> <label for="radio_1">Radio #1</label>
                <input type="radio" id="radio_2" value="2" name="radio" /> <label for="radio_2">Radio #2</label>
            </p>
            <p>
                <label for="select">Select:</label>
                <select name="select" id="select">
                    <option></option>
                    <option value="foo">foo label</option>
                    <option value="bar">bar label</option>
                    <option value="baz">baz label</option>
                </select>
            <p>
            <p>
                <label for="file">File to upload: </label>
                <input type="file" id="file" name="file" />
            </p>
            <p>
                <button type="submit" id="submit">Submit</button>
                <button type="submit" disabled="disabled" id="submit-disabled">Submit (disabled)</button>
            </p>
        </form>
        <?php if (count($_POST)): ?>
            <hr />
            <p>Text field: <span id="post-text"><?php echo isset($_POST['text']) ? htmlentities($_POST['text'], ENT_QUOTES, 'UTF-8') : '*none*'; ?></span></p>
            <p>Checkbox is <span id="post-checkbox"><?php echo isset($_POST['checkbox']) ? '' : 'not '; ?>checked</span></p>
            <p>Radio: <span id="post-radio"><?php echo isset($_POST['radio']) ? $_POST['radio'] : '*none*'; ?></span></p>
            <p>Select: <span id="post-select"><?php echo isset($_POST['select']) && $_POST['select'] ? $_POST['select'] : '*none*'; ?></span></p>
            <?php if (isset($_FILES['file'])): ?>
                <p>File uploaded: <span id="post-size"><?php echo htmlentities($_FILES['file']['size'], ENT_QUOTES, 'UTF-8'); ?></span> bytes</p>
            <?php else: ?>
                <p>No file uploaded</p>
            <?php endif ?>
        <?php endif ?>
    </body>
</html>
