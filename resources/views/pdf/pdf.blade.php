<!-- pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Document</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .document {
            width: 210mm;
            height: 297mm;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden; /* Prevent content overflow */
        }
        /* Adjust styles for content to fit within A4 format */
        h1, p, table {
            max-width: 100%;
            word-wrap: break-word;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>PDF Document</h1>
    <div class="document" id="previewContent" style=" width: 210mm; height: 297mm;">
        <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo">
    </div>
</body>
</html>
