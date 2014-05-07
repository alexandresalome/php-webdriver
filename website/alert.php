<!DOCTYPE html>
<html>
    <head>
        <title>Alert page</title>
    </head>
    <body>
        <h1>Alert tests</h1>
        <p >Feedback: <span id="alert-feedback">nothing</span></p>
        <div class="hover-wrapper">
            <h2 class="title">Hover me</h2>
            <p class="content">You see this text because of hover</p>
            <p>
                <button id="reset" onclick="test_reset()">Reset</button>
                <button id="alert" onclick="test_alert()">Alert</button>
                <button id="confirm" onclick="test_confirm()">Confirm</button>
            </p>
        </div>
        <script type="text/javascript">
            function test_reset()
            {
                document.getElementById('alert-feedback').innerHTML = 'nothing';
            }

            function test_alert()
            {
                alert("ALERT");
                document.getElementById('alert-feedback').innerHTML = 'alerted';
            }

            function test_confirm()
            {
                if (confirm("CONFIRM?")) {
                    document.getElementById('alert-feedback').innerHTML = 'confirmed';
                } else {
                    document.getElementById('alert-feedback').innerHTML = 'dismissed';
                }
            }
        </script>
    </body>
</html>
