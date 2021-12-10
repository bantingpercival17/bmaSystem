<style>
    * {
        padding: 0px;
        margin-top: 3px;
    }


    .qr {
        width: 100%;
        text-align: center;
    }

</style>


<body>
    <div class="qr">
        <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(300)->generate($_data),
) !!} ">
    </div>

</body>
