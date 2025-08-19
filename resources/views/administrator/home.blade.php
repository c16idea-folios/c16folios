@extends("$theme/layout")
@section('title') Inicio @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
@endsection
@section('styles_optional_vendors')

@endsection

@section('content_breadcrumbs') 

@endsection

@section('content_page')
<!-- begin:: Content -->
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
    <a href="{{route('clients.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home1.png" class="" width="100">
<p class="home-text1">Clientes/Comparecientes</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
<a href="{{route('instrument.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home2.png" class="" width="100">
<p class="home-text1">Instrumentos</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">

<a href="{{route('canceled.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home3.png" class="" width="100">
<p class="home-text1">Cancelados</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">

<a href="{{route('instrument_act.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home4.png" class="" width="100">
<p class="home-text1">Indice</p>
</div>
</a>
</div>

</div>
<div class="row">

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
<a href="{{route('file.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home5.png" class="" width="100">
<p class="home-text1">Expediente</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
<a href="{{route('payment.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home6.png" class="" width="100">
<p class="home-text1">Pagos</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
<a href="{{route('notification.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home7.png" class="" width="100">
<p class="home-text1">Avisos</p>
</div>
</a>
</div>

<div class="col-lg-3 col-md-6 col-sm-12 col-12 mt-4">
<a href="{{route('acts.admin')}}"  targe="_self">
<div class="card w-100  p-4 text-center">
<img class="home-img1" src="{{asset("assets")}}/images/home8.png" class="" width="100">
<p class="home-text1">Actos</p>
</div>
</a>
</div>
</div>

@endsection



@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
@endsection

@section('js_optional_vendors')

@endsection
@section('js_page_scripts')

<script>
    function openService(){
        window.open("/fleet_manager_service/12","_self")

    }
</script>
@endsection

