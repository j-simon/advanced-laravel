<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload-Formular</title>
</head>
<body>
    <form action="/uploadImage" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="bild" > <br>
        <input type="submit" value="posten">
    </form>
    @error('bild')
    {{$message}}
    @enderror
</body>
</html>