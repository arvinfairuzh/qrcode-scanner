<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://12d6-125-160-97-164.ngrok-free.app/assets/html5-qrcode.min.js"></script>
</head>

<body>
    <h1>QR Code Scanner</h1>

    <div id="reader" style="width:300px"></div>
    <p>Scanned result: <span id="result"></span></p>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('result').innerText = decodedText;
            console.log(`Code matched = ${decodedText}`, decodedResult);
            $.ajax({
                url: "{{url('scanning')}}", // Replace with your server-side endpoint URL
                method: 'POST',
                data: {
                    code: decodedText, // Sending the decodedText to the server
                    event_id: decodedText, // Sending the decodedText to the server
                },
                success: function(response) {
                    console.log('AJAX request successful:', response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                }
            });
        }

        function onScanFailure(error) {
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 500 });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>

</html>