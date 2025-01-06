<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{route('test.nia.api.post')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-content mb-3">
            <label for="">Image</label>
            <input type="file" name="image" id="">
        </div>

        <div class="form-content mb-3">
            <label for="id">Id Number</label>
            <input type="text" name="pinNumber" id="">
        </div>

        <div class="form-content mb-3">
            <button type="submit"> Submit for verification</button>
        </div>
    </form>
</body>
</html>