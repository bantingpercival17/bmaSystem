<style>
    * {
        padding: 0px;
        margin: 10px;
    }


    .qr {

        text-align: center;
    }

</style>

@php
$_data = strtolower(str_replace(' ', '-', trim($_employee->first_name . ' ' . $_employee->last_name)));
@endphp

<body>
    <span>&nbsp;{{ strtoupper($_data) }}</span>
    <br>
    <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(300)->generate(Crypt::encrypt($_employee->id)),
) !!} ">


</body>
