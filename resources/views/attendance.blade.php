<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

    <!-- JSZip for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>

    <!-- PDFMake for PDF export -->
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/vfs_fonts.js"></script>

    <!-- Buttons for CSV, Excel, and PDF exports -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <input type="text" id="qrInput" placeholder="Scan QR here" style="width: 1px; outline: none; border: none;" />
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Kehadiran</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['category']}}</td>
                    <td>{{$item['scanned']}}</td>
                    <td>{{$item['date']}}</td>
                </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
            </tfoot> --}}
        </table>
    </div>

    <!-- DataTable Initialization Script -->
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip', // Defines where the buttons appear
                searching: false,
                buttons: [
                    {
                        extend: 'csv',
                        footer: true
                    },
                    {
                        extend: 'excel',
                        footer: true
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'portrait',
                        download: 'open',
                        footer: true,
                        pageSize: 'A4',
                        title: '',
                        customize: function (doc) {
                            doc.content.splice(0, 0,
                                {
                                    margin: [0, 0, 0, 12],
                                    alignment: 'left',
                                    text: 'Laporan Kehadiran',
                                    fontSize: 16,
                                    bold: true
                                },
                            );
                        }
                    }
                ],
                ajax: `{{ url('/event/$event->event_id/table') }}`,
                columns: [
                    { data: 'name', name: 'qrcode.name' },
                    { data: 'category', name: 'qrcode.category' },
                    { data: 'scanned', name: 'scanned' },
                    { data: 'updated_at', name: 'updated_at' },
                ],
                paging: false,
            });

            $('#qrInput').focus();
            // When QR scanner inputs a result
            $('#qrInput').on('change', function() {
                var qrResult = $(this).val();
                console.log(qrResult);
                if (qrResult.length > 0) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                        }
                    });
                    $.ajax({
                        url: "{{url('scanning')}}",
                        method: 'POST',
                        data: {
                            code: qrResult,
                            event_id: "{{$event->event_id}}",
                        },
                        success: function(response) {
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-center", // Position at the bottom center
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            if(response?.status) {
                                toastr.success(response.name);
                            }
                            else {
                                toastr.error(response.message);
                            }
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            table.ajax.reload();
                            // document.getElementById('status-scanner').innerText = decodedText + ' GAGAL';
                        }
                    });
                    table.ajax.reload();
                    $(this).val('');
                }
            });

            $(document).on('click', function() {
                $('#qrInput').focus();
            });
        });
        // setInterval(function() {
        //     window.location.reload();
        // }, 5000); // 5000 milliseconds = 5 seconds
    </script>

</body>

</html>