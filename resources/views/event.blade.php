<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F7F9;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            color: #2C3A4B;
            text-align: center;
            margin-bottom: 20px;
        }

        .event-details {
            background-color: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            height: calc(100vh - 40px);
            /* Adjust height to fit full viewport minus some padding/margin */
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
        }

        #reader {
            width: 100%;
            /* height: 100%; Fill the height of its container */
            max-width: 100%;
            height: auto;
            /* Adjust height as needed or use a specific height */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">{{$event['event_name']}}</div>
        <div class="event-details" id="eventDetails">
            <div id="reader"></div>
            <div id="status-scanner-yes"
                style="background-color: rgb(114, 255, 119); width: 100%; height: 10px; border-radius: 0px 0px 5px 5px">
            </div>
            <div id="status-scanner-no"
                style="background-color: rgb(255, 114, 114); width: 100%; height: 10px; border-radius: 0px 0px 5px 5px; display: none;">
            </div>
        </div>

    </div>
    {{-- <script src="https://12d6-125-160-97-164.ngrok-free.app/assets/html5-qrcode.min.js"></script> --}}
    <script src="{{asset('/assets/html5-qrcode.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        let scanningEnabled = true; // Flag to control scanning
        function onScanSuccess(decodedText, decodedResult) {
            if (scanningEnabled) {
                 // Disable scanning
                scanningEnabled = false;
                document.getElementById('status-scanner-no').style.display = 'block';
                document.getElementById('status-scanner-yes').style.display = 'none';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                    }
                });
                $.ajax({
                    url: "{{url('scanning')}}", // Replace with your server-side endpoint URL
                    method: 'POST',
                    data: {
                        code: decodedText, // Sending the decodedText to the server
                        event_id: "{{$event->event_id}}", // Sending the decodedText to the server
                    },
                    success: function(response) {
                        if(response === '1') {
                            toastr.success("Berhasil");
                        }
                        else {
                            toastr.error(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // document.getElementById('status-scanner').innerText = decodedText + ' GAGAL';
                    }
                });
                setTimeout(() => {
                    scanningEnabled = true;
                    document.getElementById('status-scanner-no').style.display = 'none';
                    document.getElementById('status-scanner-yes').style.display = 'block';
                }, 5000); // Adjust the delay as needed
            }
        }

        function onScanFailure(error) {
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 240, qrbox: { width: window.innerWidth - 200, height: window.innerWidth - 200 } });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>

</html>