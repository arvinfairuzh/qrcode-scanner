<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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
                    <td>{{$item['scanned']}}/td>
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
        $('#example').DataTable(); // Initialize the DataTable
    });
    </script>

</body>

</html>