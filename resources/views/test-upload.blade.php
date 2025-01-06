<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


    <form action="/test/file/upload" method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="">
        @csrf
<?php
echo sys_get_temp_dir();
?>
        <button type="submit">Submit File</button>
        @php
            $s3FileUrl = Storage::disk('s3')->url('documents/mandate-doc-62b8fe79-0d32-45c6-bcf3-ddd72358a24b.pdf');
        @endphp
       <a href="{{$s3FileUrl}}"></a>
    </form>
    
</body>
</html>