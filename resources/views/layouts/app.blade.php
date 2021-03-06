<!DOCTYPE html>
<html lang="en">
    <head>
        

        <title>@yield('title','default')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
        <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">

        <link href="{{asset('plugins/MaterializeAdmin/js/plugins/prism/prism.css')}}" type="text/css" rel="stylesheet" >
        <link href="{{asset('plugins/MaterializeAdmin/js/plugins/data-tables/css/jquery.dataTables.min.css')}}" type="text/css" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link href="{{ asset('plugins/MaterializeAdmin/css/materialize.css')}}" type="text/css" rel="stylesheet">
        <link href="{{ asset('plugins/MaterializeAdmin/css/style.css')}}" type="text/css" rel="stylesheet">
        <link href="{{ asset('plugins/MaterializeAdmin/css/custom/custom.css')}}" type="text/css" rel="stylesheet">

        <link href="{{asset('plugins/tooltip/css/html5tooltips.css')}}" rel="stylesheet">
        <link href="{{asset('plugins/tooltip/css/html5tooltips.animation.css')}}" rel="stylesheet">
         
        <link href="{{ asset('plugins/MaterializeAdmin/js/plugins/sweetalert/dist/sweetalert.css')}}" type="text/css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/js/dataurl.css')}}">

        
        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        <link href="{{ asset('plugins/MaterializeAdmin/js/plugins/perfect-scrollbar/perfect-scrollbar.css')}}" type="text/css" rel="stylesheet">
        <link href="{{ asset('plugins/MaterializeAdmin/js/plugins/jvectormap/jquery-jvectormap.css')}}" type="text/css" rel="stylesheet">
        <link href="{{ asset('plugins/MaterializeAdmin/js/plugins/chartist-js/chartist.min.css')}}" type="text/css" rel="stylesheet">    


    </head>

    <body id="app-layout">
        <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left grey"></div>
      <div class="loader-section section-right grey"></div>
  </div>
        <header> 
            <div class="navbar-fixed" >
                <nav class="gradient  s12 m3 l12">
                    <div class="nav-wrapper">
                        <div class="row">
                            <div class="col s6 m6 l6 hide-on-small-only hide-on-med-only">
                                <img src="{{ asset('img/logo.png')}}" alt="Sistema control académico universitario"> 
                            </div>
                            <div class="col s1 l1 m1 offset-l5 offset-m11 offset-s10">
                               
                            <a class="" @if(Auth::guard('admin')->check()) href="{{url('/logoutdo')}}" @elseif(Auth::check()) href="{{ url('/logoutes') }}" @endif()

                              data-activates="usuario" data-position="bottom" data-delay="50" data-tooltip="asignaturas"><i class="mdi-action-settings-power left"></i></a>
                        
                            </div>

                            
                        </div>
                        
                    </div>
                </nav>
            </div>
        </header>
         
        <div id="main">    
            <div class="wrapper">       
                <section id="content">
                    <div class="container card-panel">
                        
                            @if(Auth::guard('admin')->check())
                                @include('partials.nav')
                            @else
                                @include('partials.navEstudiantes')
                                @include('admin.usuarios.modals.modificarContrasena')
                                @include('admin.usuarios.modals.verPerfil')
                            @endif
                    
                            @yield('content')
                               
                    </div>

                </section>
            </div>
        </div>
        
  
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/jquery-1.11.2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/materialize.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/prism/prism.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/data-tables/data-tables-script.js')}}"> </script>
     
    
    <!--<script src="{ {asset('plugins/jqueryui/external/jquery/jquery.js')}}"></script>-->
    <script type="text/javascript" src="{{asset('plugins/jquery/jquery.form.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('plugins/js/pace.min.js')}}"></script>     
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/sweetalert/dist/sweetalert.min.js')}}"></script>

    <!-- chartist -->
    <!--<script type="text/javascript" src="{ {asset('plugins/MaterializeAdmin/js/plugins/chartist-js/chartist.min.js')}}"></script> -->  

    <!-- chartjs -->
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/chartjs/chart.min.js')}}"></script>
    <!--<script type="text/javascript" src="{ {asset('plugins/MaterializeAdmin/js/plugins/chartjs/chart-script.js')}}"></script>-->
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/data-tables/js/jquery.dataTables.min.js')}}"></script>
    <!-- sparkline -->
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins/sparkline/sparkline-script.js')}}"></script>
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/plugins.js')}}"></script>
    <!--custom-script.js - Add your own theme custom JS-->
    <script type="text/javascript" src="{{asset('plugins/MaterializeAdmin/js/custom-script.js')}}"></script>

    <!-- Custom functions file -->
    <script type="text/javascript" src="{{asset('plugins/tooltip/functions.js')}}"></script>

    <!-- html5tooltips script -->
    <script  type="text/javascript" src="{{asset('plugins/tooltip/html5tooltips.js')}}"></script>

    <!-- Tabla Editable -->
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/plugins/editable-table/numeric-input-example.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/plugins/editable-table/mindmup-editabletable.js')}}"></script>
    <!-- Tabla Flotante -->
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/plugins/floatThead/jquery.floatThead.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/plugins/floatThead/jquery.floatThead-slim.min.js') }}"></script>
    <!-- BlockUI -->
    <script type="text/javascript" src="{{ asset('plugins/MaterializeAdmin/js/jquery.blockUI.min.js') }}"></script>
    
   @yield('scripts')
        <script type="text/javascript">
                
            $( document ).ready(function(){
                $('.button-collapse').sideNav();
                $('.collapsible').collapsible();     
                $('.tooltipped').tooltip({delay: 50});
                     
            });

            function modificarContrasena(estado){
                if(estado == 1){
                    $('#modificarContrasena').openModal();
                }else{
                    $('#modificarContrasena').openModal({
                            dismissible: false
                        });  
                    }
                }  

            function verPerfil(estado){
                    $('#verPerfil').openModal();     
                }       
             

        </script>
        
        <!--cambiar contraseña por defecto solo si es estudiante-->
        @if(Auth::guard('admin')->check())

        @else
            @if(Auth::user()->estadoContrasena == 0)
                <script type="text/javascript">
                    modificarContrasena();
                </script>
                
            @endif    
        @endif  
    </body>
</html>
