<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Document</title>

    <style>
        .modal-content{
            font-size: 13px;
        }
    </style>

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
   
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="print-cont" style="width: 595px;">
                <div class="modal-header">
                </div>
                <div class="modal-body" id="previewContent">
                    {!!$previewContent!!}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

     {{-- <div class="document" id="previewContent" style="width: 210mm; height: 297mm;">
        {!!$previewContent!!}
    </div> --}}
</body>
</html>