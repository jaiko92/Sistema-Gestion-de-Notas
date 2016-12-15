<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/',['middleware' => 'guest',function () {
    return view('bienvenido.bienvenido');
}] );



Route::group(['middleware' => 'auth'],function(){

   Route::get('/index',function(){
      return view('welcome');
    });

   // Rutas para el administrador
  Route::group( ['prefix'=>'admin', 'middleware' => 'administrador' ],function(){

  

    Route::get('verDatosAsignatura/{id}',[
        'uses' => 'AsignaturasController@verDatosAsignatura',
        'as' => 'admin.asignaturas.verDatosAsignatura'
      ]);

      Route::get('profesores',[
          'uses' => 'ProfesoresController@index',
          'as' => 'admin.profesores.index'
        ]);


      Route::post('item',[
        'uses' => 'ItemsController@store',
        'as' => 'items.store'
        ]);

      Route::post('subitem',[
        'uses' => 'SubitemsController@store',
        'as' => 'subitems.store'
      ]);

     Route::get('item/{id}/destroy',[
        'uses' => 'ItemsController@destroy',
        'as' => 'item.destroy'
        ]);

      Route::get('subitem/{id}/destroy',[
        'uses' => 'SubitemsController@destroy',
        'as' => 'subitem.destroy'
        ]);

     Route::get('nota',[
          'uses' => 'NotasController@storeItem',
          'as' => 'nota.store.item'
      ]);

      Route::get('notaSubitem',[
          'uses' => 'NotasController@storeSubitem',
          'as' => 'nota.store.subitem'
      ]);

     Route::get('MisAsignaturas',[
        'uses' => 'MatriculasController@index',
        'as' => 'matriculas.index'

      ]);

      Route::get('asignaturas',[
          'uses' => 'AsignaturasController@index',
          'as' => 'admin.asignaturas.index'
        ]);

      Route::resource('informes','InformesController');


      //reporte Asignatura
      Route::get('informes/pdfAsignatura/{idHorario}',[
        'uses'=>'InformesController@ejecutarReporteAsignatura',
        'as'=>'admin.informes.pdfAsignatura'
        ]);
      //reporte Profesores
      Route::get('informes/pdfProfesor/{idProfesor}/{idPeriodo}/{idPrograma}',[
        'uses'=>'InformesController@crearReporteProfesor',
        'as'=>'admin.informes.pdfProfesor'
        ]);
            

      Route::resource('estudiantes','EstudiantesController');
     
        //cargar informacion en el modal listar asignaturas Estudiante
      Route::GET('estudiantes/listarAsignaturas/{id}',[
        'uses' =>'EstudiantesController@listarAsignaturas',
        'as' => 'admin.estudiantes.listarAsignaturas'
        ]);   
      
      //cargar informacion en el modal eliminar
      Route::GET('estudiantes/{id}/destroy',[
        'uses' =>'EstudiantesController@destroy',
        'as' => 'admin.estudiantes.destroy'
        ]);
       // activa la accion eliminar en el modal
       Route::put('estudiantes/{id}/destroyupdate',[
         'uses' =>'EstudiantesController@destroyupdate',
          'as' => 'admin.estudiantes.destroyupdate'
        ]);
       
       //guardar Estudiantes
       Route::post('estudiantes/guardar',[
         'uses' =>'EstudiantesController@guardarEstudiante',
          'as' => 'admin.estudiantes.guardarEstudiante'
        ]);

       //cargar informacion editarEstudiante
       Route::get('estudiantes/editar/{id}',[
         'uses' =>'EstudiantesController@edit',
          'as' => 'admin.estudiantes.edit'
        ]);

       //editarEstudiante
       Route::post('estudiantes/editar',[
         'uses' =>'EstudiantesController@editar',
          'as' => 'admin.estudiantes.editar'
        ]);
       //procesarArchivo
       Route::POST('estudiantes/procesar',[
        'uses' =>'EstudiantesController@procesarArchivo',
        'as' => 'admin.estudiantes.procesarArchivo'
        ]); 

        Route::get('filtrosAsignaturas',[
         'uses' =>'AsignaturasController@filterAjax',
          'as' => 'admin.asignaturas.filterAjax'
        ]);

        Route::GET('filtrosProfesores',[
         'uses' =>'ProfesoresController@filterAjax',
          'as' => 'admin.profesores.filterAjax'
        ]);

        Route::GET('verProfesor/{id}/{idprograma}',[
         'uses' =>'ProfesoresController@ver',
          'as' => 'admin.profesores.ver'
        ]);

        //Ruta index notas
        Route::get('notas/{id}',[
          'uses' => 'NotasController@index',

          'as' => 'admin.notas.index'
          ]);

    });
//fin middleware administrador

    Route::group(['prefix'=>'docente','middleware' => 'docente'],function(){

      Route::post('item',[
        'uses' => 'ItemsController@store',
        'as' => 'items.store'
        ]);

      Route::post('subitem',[
        'uses' => 'SubitemsController@store',
        'as' => 'subitems.store'
      ]);

     Route::get('item/{id}/destroy',[
        'uses' => 'ItemsController@destroy',
        'as' => 'item.destroy'
        ]);

      Route::get('subitem/{id}/destroy',[
        'uses' => 'SubitemsController@destroy',
        'as' => 'subitem.destroy'
        ]);

     Route::get('nota',[
          'uses' => 'NotasController@storeItem',
          'as' => 'nota.store.item'
      ]);


      Route::get('notaSubitem',[
          'uses' => 'NotasController@storeSubitem',
          'as' => 'nota.store.subitem'
      ]);

      Route::get('MisAsignaturas',[
        'uses' => 'MatriculasController@index',
        'as' => 'matriculas.index'

      ]);

      Route::get('notas/{id}',[
          'uses' => 'NotasController@index',
          'as' => 'notas.index'
        ]);

        Route::post('matricular/archivo',[
          'uses' =>'MatriculasController@matricularEstudiantes',
          'as' => 'matricular.archivo'
        ]);

        Route::post('matricular/estudiante',[
          'uses' =>'MatriculasController@store',
          'as' => 'matricular.estudiante'
          ]);


    });
//fin middleware coordinador

});


//rutas para el estudiante
  Route::group(['prefix'=>'admin'], function(){
    Route::get('asignaturasEstudiante',[
          'uses' => 'EstudiantesController@asignaturasEstudiante',
          'as' => 'admin.usuarios.asignaturasEstudiante'
    ]);


  });

Route::get('login/estudiantes',[
 'uses' => 'Auth\AuthController@showLoginForm',
    'as' => 'user.login'
]);

Route::get('login/docentes',[
 'uses' => 'Admin\AuthController@showLoginForm',
    'as' => 'admin.login'
]);


Route::post('login/estudiantes',[
    'uses' => 'Auth\AuthController@login',
    'as' => 'user.login'
]);


Route::post('login/docentes',[
    'uses' => 'Admin\AuthController@login',
    'as' => 'admin.login'
]);


Route::get('matricular/autocomplete','MatriculasController@autocomplete');

Route::get('estudiantes','MatriculasController@index');


  

  //Route::get('/home', 'HomeController@index');



   //Route::post('redirigir','autenticacionController@obtenerControlador');
   //Route::post('login','Admin\AdminAuthController@login');
   //Route::get('login', 'Auth\AuthController@showLoginForm');
   //Route::post('login','Auth\AuthController@login');
   Route::get('logoutes','Auth\AuthController@logout');

  Route::get('logoutdo','Admin\AuthController@logout');

  Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
  Route::post('password/reset', 'Auth\PasswordController@reset');
   
  //Route::get('archivo','MatriculasController@matricularEstudiantes');
  Route::get('encabezado','MatriculasController@leerEncabezado');

  Route::get('materias/','MatriculasController@materias');


  

  



   



