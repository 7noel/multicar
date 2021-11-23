<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <!-- <script src="/js/app.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    {!! Html::script('js/general.js') !!}
    <script>
$(document).ready(function () {
    if ($('#CondPago').val()!='CONTADO') {
        $('.credito').removeClass('hidden')
        $('.cuota2').addClass('hidden')
        fecha_1 = new Date($('#fecha').val()+'T00:00')
        fecha_1.setDate(fecha_1.getDate() + 30)
        $('#fecha_1').val(fecha_1.toISOString().slice(0, 10))
        total = $('#total').val()
        if ($('#detraccion').is(":checked")) {
            total = $('#neto').val()
        }
        $('#cuota_1').val(total)
        calcular_dias()
    } else {
        $('.credito').addClass('hidden')
        calcular_dias()
    }
    if ($('#detraccion').is(":checked")) {
        $('.detraccion').removeClass('hidden')
    } else {
        $('.detraccion').addClass('hidden')

    }
    $('#CondPago').change(function (e) {
        if ($('#CondPago').val()!='CONTADO') {
            $('.credito').removeClass('hidden')
            cuotas = $('#cuotas').val()
        } else {
            $('.credito').addClass('hidden')

        }
        recalcularTotales()
    })

    $('#detraccion').change(function (e) {
        if ($('#detraccion').is(":checked")) {
            $('.detraccion').removeClass('hidden')
        } else {
            $('.detraccion').addClass('hidden')
        }
        recalcularTotales()
    })
    $('#cuotas').change(function (e) {
        recalcularTotales()
    })
    $('#cuota_1').change(function (e) {
        total = parseFloat($('#total').val())
        cuota_1 = parseFloat($('#cuota_1').val())
        if ($('#detraccion').is(":checked")) {
            total = parseFloat($('#neto').val())
        }
        cuota_2 = total - cuota_1
        $('#cuota_2').val(cuota_2)
    })
    $('#cuota_2').change(function (e) {
        total = parseFloat($('#total').val())
        cuota_2 = parseFloat($('#cuota_2').val())
        if ($('#detraccion').is(":checked")) {
            total = parseFloat($('#neto').val())
        }
        cuota_1 = total - cuota_2
        $('#cuota_1').val(cuota_1)
    })
    $('#fecha_1').change(function (e) {
        if ($('#cuotas').val() == 1) {
            $('#FechaVence').val($('#fecha_1').val())
            calcular_dias()
        }
    })
    $('#fecha_2').change(function (e) {
        if ($('#cuotas').val() == 2) {
            $('#FechaVence').val($('#fecha_2').val())
            calcular_dias()
        }
    })
    function recalcularTotales() {
        $('.detraccion').removeClass('hidden')
        if ($('#CondPago').val()!='CONTADO') {
            if ($('#cuotas').val() == 1) {
                $('.cuota2').addClass('hidden')
                fecha_1 = new Date($('#fecha').val()+'T00:00')
                fecha_1.setDate(fecha_1.getDate() + 30)
                $('#fecha_1').val(fecha_1.toISOString().slice(0, 10))
                total = $('#total').val()
                if ($('#detraccion').is(":checked")) {
                    total = $('#neto').val()
                }
                $('#cuota_1').val(total)
                $('#FechaVence').val($('#fecha_1').val())
            }
            if ($('#cuotas').val() == 2) {
                $('.cuota2').removeClass('hidden')
                fecha_1 = new Date($('#fecha').val()+'T00:00')
                fecha_1.setDate(fecha_1.getDate() + 15)
                $('#fecha_1').val(fecha_1.toISOString().slice(0, 10))
                fecha_2 = new Date($('#fecha').val()+'T00:00')
                fecha_2.setDate(fecha_2.getDate() + 30)
                $('#fecha_2').val(fecha_2.toISOString().slice(0, 10))

                total = parseFloat($('#total').val())
                if ($('#detraccion').is(":checked")) {
                    total = parseFloat($('#neto').val())
                }
                cuota_1 = Math.round(total*100/2)/100
                cuota_2 = total - cuota_1
                $('#cuota_1').val(cuota_1)
                $('#cuota_2').val(cuota_2)
                $('#FechaVence').val($('#fecha_2').val())
            }
        } else {
            $('#FechaVence').val($('#fecha').val())
            //$('#Dias').val('0')
        }
        calcular_dias()
    }
    function calcular_dias() {
        day1 = new Date($('#fecha').val())
        day2 = new Date($('#FechaVence').val())
        difference= Math.abs(day2-day1)
        days = difference/(1000 * 3600 * 24)
        // console.log($('#fecha').val())
        // console.log($('#FechaVence').val())
        // console.log(days)
        $('#Dias').val(days)
    }
});
    </script>
</body>
</html>
