<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Импорт товаров</title>
</head>
<body>
<div id="product-importer">
    <form method="post" enctype="multipart/form-data" action="products/import" id="product-importer-form">
        @csrf
        <input type="file" id="product-importer-input" name="upload">
        <button type="submit" id="product-importer-submit">Импорт</button>
    </form>
    <div id="import-result"></div>
</div>
</body>
<script type="text/javascript">
    $('#product-importer-form').on('submit', function (event) {
        event.preventDefault();
        let postData = new FormData();
        postData.append("_token", "{{ csrf_token() }}");
        postData.append("upload", document.getElementById("product-importer-input").files[0]);
        $.ajax({
            url: "products/import",
            type: "POST",
            processData: false,
            contentType: false,
            data: postData,
            success: function (data) {
                $('#import-result').html(data);
            }
        });
    });
</script>
</html>
