
@extends('layouts.app')
@section('title','Estudiantes')
@section('content')

<h3 class="center">Estudiantes</h3>
 <br>
<!--campo buscar y registrar-->
   <div class="row">
      <div class="col s12 m12 l12">
         <fieldset class="grey lighten-4"> 
            <div class="row">
            
               <div class="input-field col s10 l4 m4 fuentes" >
                    @if (Auth::guard('admin')->user()->rolAdministrador())
                        <select id="programas" name="programas">
                            <option value="" disabled selected>Seleccione un programa</option>
                            @foreach($programas as $programa)
                                @if($programa->NombrePrograma != 'GENERICO')
                                    <option value="{{$programa->CodigoPrograma}}" id="{{$programa->Id}}">{{$programa->NombrePrograma}}</option>
                                @endif
                            @endforeach
                        </select>
                            <label>Programa Académico</label>
                    @elseif (Auth::guard('admin')->user()->rolCoordinador())
                        <select id="programas" name="programas">
                            @foreach(Auth::guard('admin')->user()->usuarios[0]->programasAcademicos as $programa)
                           
                                <option value="{{$programa->CodigoPrograma}}" id="{{$programa->Id}}">{{$programa->NombrePrograma}}</option>
                            
                            @endforeach

                        </select> 
                        <label>Programa Académico</label>                   
                    @endif            
                </div>
                @if(Auth::guard('admin')->user()->rolCoordinador())
                    <div class="col s1 m1 l1">
                             <i class=" mdi-communication-live-help blue-text" data-tooltip="Si coordinas mas programas puedes filtrar por el programa que desees"  data-tooltip-animate-function="slidein" data-tooltip-stickto="right"  data-tooltip-color="#424242" data-tooltip-maxwidth="200"></i>
                </div>      
                @elseif(Auth::guard('admin')->user()->rolAdministrador())

                    <div class="col s1 m1 l1">
                             <i class=" mdi-communication-live-help blue-text" data-tooltip="Puedes realizar un filtrado por el programa académico que desees"  data-tooltip-animate-function="slidein" data-tooltip-stickto="right"  data-tooltip-color="#424242" data-tooltip-maxwidth="200"></i>
                    </div> 
                @endif
            

               <div class="col s12 m5 l3 offset-l4" style="padding-top: 25px;">
                  <a onClick='openModalCrear()'  class=" waves-effect waves-light btn green lighten-2  modal-trigger" data-target='#crearEstudiante'>Registrar Estudiante</a> 
               </div> 
               </div>   
            
         </fieldset>

         @if (session()->has('flash_notification.message'))
               <div id="card-alert" class="card {{ session('flash_notification.level') }}" style="height: 1%">
                     <div class="card-content white-text">
                        <p>   @if(session('flash_notification.level')=='success')
                              <i class="mdi-navigation-check"></i>
                           @elseif(session('flash_notification.level')=='danger')
                              <i class="mdi-alert-error"></i> 
                           @elseif(session('flash_notification.level')=='warning')
                              <i class="mdi-alert-warning"></i> 
                           @elseif(session('flash_notification.level')=='info')
                              <i class="mdi-action-info-outline"></i> 
                           @endif
                     {!! session('flash_notification.message') !!}</p>
              
                     </div>
               </div>
            @endif
         <br>

         <input type="hidden" id="idPrograma">
         <div class="row">
            <div class="col s1 m1 l1 offset-l11 offset-m11 offset-s9">
              
               <i class="mdi-action-help blue-text" data-tooltip="Hola, esta sección contiene todos los estudiantes de la sede, puedes registrar estudiantes de manera individual o procesar un archivo plano para registrar muchos estudiantes, puedes editar su informaciónn y consultar sus datos"  data-tooltip-animate-function="slidein" data-tooltip-stickto="left"  data-tooltip-color="#424242" data-tooltip-maxwidth="300"></i>

            </div>     
        </div>
       
         <div class="divider grey darken-1"></div>
         <br>

         <div class="row" >
            <div class="col s12 m12 l12" id="Estudiantes">

               <table class="responsive-table  bordered">
                  <thead>
                     <tr>
                        <th data-field="name">Código</th>
                        <th data-field="id">Nombre Completo</th>
                        <th data-field="email">Correo</th>
                        <th data-field="programa">Programa</th>
                        <th data-field="accion">Acciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     
                 
                  </tbody>
               </table>

              
         </div>

      </div>
   </div>
 </div>     
      
@include('admin.usuarios.modals.crearEstudiante',['programas' => $programas])
@include('admin.usuarios.modals.eliminarEstudiante')
@include('admin.usuarios.modals.editarEstudiante',['programas'=> $programas])
@include('admin.usuarios.modals.cargarEstudiante')
@include('admin.usuarios.modals.listarAsignaturas',['periodos'=>$periodos])

@overwrite

@section('scripts')

<script type="text/javascript">

   function listarAsignaturas(id){
    $('#periodos').material_select();
    $('#listarAsignaturas').openModal();
    $('#idEstudiantehidden').remove();
      consultaModalListar(id);
   }
   function generarPdf(){
    idEstudiante=$('#idEstudiantehidden').val();
    idPeriodo=$('#periodos').val();
    var url="{{route('admin.informes.pdfEstudiante',['id','idPeriodo'])}}"
    url=url.replace('id',idEstudiante);
    url=url.replace('idPeriodo',idPeriodo);

    window.open(url);
   }

   $('#periodos').change(function(){
    consultaModalListar();
   })

   function consultaModalListar(id){
    var idEstudiante;
    idPeriodo=$('#periodos').val();

    if(typeof($('#idEstudiantehidden').val()) === "undefined"){
      idEstudiante=id;
    }else{idEstudiante=$('#idEstudiantehidden').val(); }

     var ruta= "{{route('admin.estudiantes.listarAsignaturas',['idEstudiante','idPeriodo'])}}";
     ruta=ruta.replace('idEstudiante',idEstudiante);
     ruta=ruta.replace('idPeriodo',idPeriodo);
      
      $.ajax({
         url:ruta,
         type:'GET',
         dataType:'json',
         data:{idEstudiante:idEstudiante,idPeriodo:idPeriodo},
         success:function(res){
          console.log(res);
          $('#divListarAsignaturas').empty();
          $('#divListarAsignaturas').append(res);     
         },
         error:function(error){
            console.log(error);
         }
      });
   }
</script>

<!--abrir selectores-->
<script type="text/javascript"> 
   $(document).ready(function(){
      consulta();
      $("#eliminarEstudiante").addClass("modalEliminar");
      $("#listarAsignaturas").addClass("modalAsignaturaEstudiantes");
      $('#selectorPrograma1').material_select();
      $('#programas').material_select();
         
      });
   </script>

<!-- capturar selector crear -->
<script type="text/javascript">
   $('#selectorPrograma2').change(function() {
      var opcion = $(this).children(":selected").attr("value");
      $('#programaAcademico').val(opcion);
   });
</script>

<!-- capturar selector editar-->
<script type="text/javascript">
   $('#selectorPrograma1').change(function() {
      var opcion = $(this).children(":selected").attr("value");
      $('#id_programaAcademico').val(opcion);
   });
</script> 

<script type="text/javascript">
   function abrirCargarArchivo(){
      console.log('si');
         // $('#crearEstudiante').closeModal();
         //window.location= "{{route('admin.estudiantes.index')}}";
      $('#cargarEstudiante').openModal();
      $('#cargarEstudiante').openModal();
   }
</script>

   <script type="text/javascript">

      $('#programas').change(function(){
         consulta();
      });

      function consulta(){
         var ruta = "{{route('admin.estudiantes.index')}}";
         var idPrograma = $('#programas').val();
         $('#data-table-estudiante').DataTable({
                retrieve:true
            }).destroy();
         $.ajax({
            url:ruta,
            type:'GET',
            dataType:'json',
            data:{idPrograma :idPrograma},
            success:function(data){
               $("#Estudiantes").html(data);
               $('#data-table-estudiante').DataTable({
            "language":{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
                    });
            },
            error:function(xml,error,error2){
               console.log(error);
            }
         });
      } 
   

      
      function eliminar(id){
         var rutaBusqueda="{{route('admin.estudiantes.destroy',['%iduser%'])}}" ;
         rutaBusqueda = rutaBusqueda.replace('%iduser%',id); 
         $.get(rutaBusqueda,function(res){
            var nombre = res.primerNombre+" "+res.segundoNombre+" "+res.primerApellido;
            swal({
               title: "¿Estas seguro de desactivar el Estudiante?",
               text: nombre ,
               type: "warning",
               showCancelButton: true,
               cancelButtonColor:'#388E3C',
               confirmButtonColor: '#E53935',
               confirmButtonText: 'Si, Desactivarlo',
               cancelButtonText: "Cancelar",
               closeOnConfirm: false,
               closeOnCancel: false
            },
            function(isConfirm){
               if (isConfirm){
                  
                  var rutaEliminar="{{route('admin.estudiantes.destroyupdate',['%iduser%'])}}" ;
                  rutaEliminar = rutaEliminar.replace('%iduser%',id);
                  var token = $('#token').val();
                  $.ajax({
                     url:rutaEliminar,
                     headers:{'X-CSRF-TOKEN': token},
                     type: 'PUT',
                     dataType:'json',
                     data:{id},
                     success:function(){
                        window.location= "{{route('admin.estudiantes.index')}}";
                     }
                  });

               }else {
                  
                  swal("Cancelado", "El Estudiante no se ha desactivado", "error");
                 
               }
            });
         }); 
      }
    
      function openModalCrear() {
         $('#crearEstudiante').openModal();
      }

      function abrirModalEditar(id){
         var ruta="{{route('admin.estudiantes.edit',['%iduser%'])}}" ;
         ruta = ruta.replace('%iduser%',id);

         $.get(ruta,function(res){ 
            $('#editarEstudiante').openModal();
            $('#selectorPrograma2 option').remove();
            $('#id').val(res[1].id);
            nombre = res[1].primerNombre+" "+res[1].segundoNombre+" "+res[1].primerApellido+" "+res[1].segundoApellido;
            $("#nombreEditar").text(nombre);
            $("#firstname").val(res[1].primerNombre);
            $("#segundoNombre").val(res[1].segundoNombre);
            $("#primerApellido").val(res[1].primerApellido);
            $("#segundoApellido").val(res[1].segundoApellido);
            $("#email").val(res[1].email);
            $("#codigo2").val(res[1].codigo);

            $('#selectorPrograma2').append('<option disable selected> Seleccione un programa</option>');
            for (var i =0; i < res[0].length; i++) {
               $('#selectorPrograma2').append('<option value='+res[0][i].CodigoPrograma+'>'+res[0][i].NombrePrograma +'</option>');
            }
    
            $('#selectorPrograma2').material_select();
         });   
      }
   </script> 

<!-- Bloquear pantalla -->
   <script type="text/javascript">
      $(document).ready(function(){
         var option= { 
            target:'#output2',
            dataType:  'json', 
            beforeSubmit:showRequest,   // target element(s) to be updated with server response 
            success:showResponse // post-submit callback 
         };
         $('#formularioSubir').submit(function() { 
            // inside event callbacks 'this' is the DOM element so we first 
            // wrap it in a jQuery object and then invoke ajaxSubmit 
            $(this).ajaxSubmit(option); 
 
            // !!! Important !!! 
            // always return false to prevent standard browser submit and page navigation 
            return false; 
         });  
      });

      function showRequest(formData, jqForm, options) { 
         var queryString = $.param(formData); 
         jsShowWindowLoad();
         console.log('About to submit: \n\n' + queryString); 
         return true; 
      }   

      function showResponse(data)  { 
         jsRemoveWindowLoad();
         console.log(data);
         window.location="{{route('admin.estudiantes.index')}}";
      } 

      function jsRemoveWindowLoad() {
         // eliminamos el div que bloquea pantalla
         $("#WindowLoad").remove();
      }
 
      function jsShowWindowLoad(mensaje) {
         console.log('si');
         $('#crearEstudiante').closeModal();
         //eliminamos si existe un div ya bloqueando
         jsRemoveWindowLoad();
         //si no enviamos mensaje se pondra este por defecto
         if (mensaje === undefined) mensaje = "Procesando la información<br>Espere por favor";
 
         //centrar imagen gif
         height = 20;//El div del titulo, para que se vea mas arriba (H)
         var ancho = 0;
         var alto = 0;
 
         //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
         if (window.innerWidth == undefined) ancho = window.screen.width;
         else ancho = window.innerWidth;
            if (window.innerHeight == undefined) alto = window.screen.height;
            else alto = window.innerHeight;
 
         //operación necesaria para centrar el div que muestra el mensaje
         var heightdivsito = alto/2 - parseInt(height)/2;//Se utiliza en el margen superior, para centrar
 
         //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
         imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + mensaje + "</div><img src={{asset('img/load.gif')}}></div>";
 
         //creamos el div que bloquea grande------------------------------------------
         div = document.createElement("div");
         div.id = "WindowLoad"
         div.style.width = ancho + "px";
         div.style.height = alto + "px";
         $("body").append(div);
 
         //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
         input = document.createElement("input");
         input.id = "focusInput";
         input.type = "text"
 
         //asignamos el div que bloquea
         $("#WindowLoad").append(input);
 
         //asignamos el foco y ocultamos el input text
         $("#focusInput").focus();
         $("#focusInput").hide();
 
         //centramos el div del texto
         $("#WindowLoad").html(imgCentro);
       
      }
   </script>

<!-- funcion enviar archivo .txt por ajax -->
   
   <style>
      #WindowLoad
      {
         position:fixed;
         top:0px;
         left:0px;
         z-index:3200;
         filter:alpha(opacity=65);
         -moz-opacity:65;
         opacity:0.65;
         background:#999;
      }
   </style>
@endsection




 