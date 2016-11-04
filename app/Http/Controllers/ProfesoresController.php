<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Http\Requests;
use App\ModelosSCAD\Usuario;
use App\ModelosSCAD\Horario;
use App\ModelosSCAD\Programaacademico;
use App\ModelosSCAD\ProgramaacademicoAsignatura;
use App\ModelosSCAD\Periodoacademico;
use Maatwebsite\Excel\Facades\Excel;



class ProfesoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $ProgramasAcademicos = Programaacademico::all(); 
        $PeriodosAcademicos = Periodoacademico::all();

        $profesores = Horario::distinct()
                                ->join('programaacademico_asignatura', 'horario.AsignaturaId' ,'=' ,                   'programaacademico_asignatura.Id')
                                ->join('programaacademico', 'programaacademico_asignatura.programaacademicoId', '=' ,'programaacademico.Id')
                                ->join('usuario','horario.UsuarioID','=','usuario.Id')
                                ->select('programaacademico.Id','usuario.id','usuario.Nombre','usuario.Apellidos','programaacademico.NombrePrograma')
                                ->orderBy('usuario.Nombre')->paginate(10);

      return view('admin.profesores.profesoresIndex')->with('profesores',$profesores)->with('ProgramasAcademicos',$ProgramasAcademicos)->with('PeriodosAcademicos',$PeriodosAcademicos);
    
    }

     public function filterAjax(Request $request){

         $profesores = Horario::distinct()
                                ->join('programaacademico_asignatura', 'horario.AsignaturaId' ,'=' ,                   'programaacademico_asignatura.Id')
                                ->join('programaacademico', 'programaacademico_asignatura.programaacademicoId', '=' ,'programaacademico.Id')
                                ->join('usuario','horario.UsuarioID','=','usuario.Id')
                                ->select('programaacademico.Id','usuario.id','usuario.Nombre','usuario.Apellidos','programaacademico.NombrePrograma')->where('horario.PeriodoAcademicoId','=',$request->get('periodo'))->where('programaacademico_asignatura.programaacademicoId', '=', $request->get('programa'))
                                ->orderBy('usuario.Nombre')->paginate(10);  
                                
        $vista = view('admin.profesores.partialTable')->with('profesores',$profesores); 
         
        if ($request->ajax()) {
            return response()->json($vista->render());
        } 
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function cargarMateria(){

        Excel::selectSheetsByIndex(0)->load('public/listado.xls',function($archivo)
        {

            //$resultado = $archivo->limit(false,3);
            $archivo->noHeading();
            $archivo->skip(3);
            $resultado = $archivo->toArray();
           
            dd($resultado);

        //$archivo->each(array)



            foreach ($resultado as $materias) {
                # code...

                echo $materias->codigo."<br>";

               /* foreach ($materias as $materia) {
                    # code...
                    echo $materia->codigo."<br>";
                }*/
                
            }

        });

        //$excel = Excel::load('public/listado.xls')->all()->toArray();
        //dd($excel);




    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function paginateArray($data, $perPage)
    {
        $page = Paginator::resolveCurrentPage();
        $total = count($data);
        $results = array_slice($data, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

}
