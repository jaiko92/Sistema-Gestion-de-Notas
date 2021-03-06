<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelosSCAD\Periodoacademico;
use App\ModelosSCAD\Programaacademico;
use App\ModelosSCAD\Horario;
use App\ModelosNotas\Estudiante;
use App\ModelosNotas\Item;
use App\ModelosNotas\Matricula;
use App\ModelosSCAD\Usuario;
use Carbon\Carbon;
use Dompdf\Dompdf;


use App\Http\Requests;

class InformesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $PeriodosAcademicos = Periodoacademico::orderBy('Id','DESC')->get();
        $programas = Programaacademico::all();

        return view('admin.informes.informesIndex')->with('PeriodosAcademicos',$PeriodosAcademicos)->with('programas',$programas);    
    }

    public function crearPdf($vista){  
     $pdf= \App::make('dompdf.wrapper');
     $pdf->loadHTML($vista);  
     /*if($identificar == 1){ return $pdf->stream('reporte');}
     if($identificar == 2){ return $pdf->download('reporte.pdf');}*/

     return $pdf->stream('ReporteSCAU.pdf');
    }

    public function crearReporteAsignatura($id){
        $vistaurl="admin.informes.partes.reportePrincipal";
        $string=$this->crearReporteAsignaturaIterar($id);
        $hora=new Carbon();
        $vista= \View::make($vistaurl,compact('string','hora'))->render();
    
        return $this->crearPdf($vista); 
    }
    public function crearReporteAsignaturaIterar($id){

        $vistaurl="admin.informes.partes.reporteAsigPart";
        $asignatura = Horario::find($id);
        $estudiantes = $asignatura->matriculas;
        $itemsPerdidos=$this->notasPerdidasEstudiantes($estudiantes);
        $ponderadoDefinitiva= $this->ponderadoNotaFinal($estudiantes);
        $vistaString= \View::make($vistaurl,compact('estudiantes','asignatura','itemsPerdidos','ponderadoDefinitiva'))->render();
        
       return $vistaString; 
    }

   
    public function notasPerdidasEstudiantes($matriculas){
        $items=array();
        foreach ($matriculas[0]->items as $item) {
        $items[]=["nombre"=>$item->nombre,"reprobados"=>0,"aprobados"=>0,"aprobadosNotaFinal"=>0,"reprobadosNotaFinal"=>0];
        }

        foreach ($matriculas as $matricula) {
            $cantidad = null;  
            foreach ($matricula->items as $item) {
                for ($i=0; $i<count($items); $i++) { 
                    if($items[$i]['nombre'] == $item->nombre){  
                        if($item->pivot->nota < 3 and !empty($item->pivot->nota) ){
                            $items[$i]['reprobados']= $items[$i]['reprobados'] + 1;  
                            }else if(!empty($item->pivot->nota)){$items[$i]['aprobados']= $items[$i]['aprobados'] + 1;}             
                    }         
                }
            }           
        }
        return $items;
    }

    public function ponderadoNotaFinal($matriculas){
        $ponderado=["aprobados"=> 0,"reprobados"=>0];

        foreach ($matriculas as $matricula) {
                if($matricula->definitiva < 3){
                    $ponderado['reprobados']= $ponderado['reprobados'] + 1; 
                }else{$ponderado['aprobados']= $ponderado['aprobados'] + 1; }  
        }

        return $ponderado;
    }

    public function crearReporteProfesor($idProfesor,$idPeriodo,$idPrograma){
        $string ="";
        $profesor= Usuario::find($idProfesor);
        $programa = Programaacademico::find($idPrograma);
        $periodo=Periodoacademico::find($idPeriodo);
        $vistaurlProfesor= "admin.informes.partes.reporteProfesor";
        $vistaurlReporte="admin.informes.partes.reportePrincipal";
        $materias = Horario::join('programaacademico_asignatura','horario.AsignaturaId' ,'=','programaacademico_asignatura.Id')
        ->join('programaacademico', 'programaacademico_asignatura.programaacademicoId', '=' ,'programaacademico.Id')
        ->join('asignatura', 'programaacademico_asignatura.AsignaturaId', '=' ,'asignatura.Id')
        ->join('usuario','horario.UsuarioID','=','usuario.Id')
        ->select('usuario.Id as idUsuario','usuario.Nombre as name','usuario.Apellidos','asignatura.Nombre', 'asignatura.Codigo','asignatura.Creditos','horario.Grupo','horario.Id')
        ->where('usuario.Id',$idProfesor)
        ->where('horario.PeriodoAcademicoId','=',$idPeriodo)
        ->where('programaacademico.Id','=',$idPrograma)->get();

        foreach($materias as $materia){
            if(count($materia->matriculas) > 0){
                $string.=$this->crearReporteAsignaturaIterar($materia->Id);   
            }
        }

        $string= \View::make($vistaurlProfesor,compact('profesor','programa','periodo','materias','string'))->render();

        $hora=new Carbon();
        $vista= \View::make($vistaurlReporte,compact('string','hora'))->render();
    
        return $this->crearPdf($vista);
        
    }


    public function crearReporteEstudiante($idEstudiante,$idPeriodo){
      $estudiante = Estudiante::find($idEstudiante);
      $periodo=Periodoacademico::find($idPeriodo);
      $vistaurlestudiante="admin.informes.partes.reporteEstudiante";
      $vistaurlReporte="admin.informes.partes.reportePrincipal";
      $matriculas=$estudiante->matriculas;
      $matriculasPorPeriodo=array();
      $asignaturas = array();

      foreach ($matriculas as $matricula) {
        if ($matricula->horario->PeriodoAcademicoId == $idPeriodo) {
        array_push($asignaturas,$matricula->horario);
        array_push($matriculasPorPeriodo,$matricula);
        }        
      }
      
      $string= \View::make($vistaurlestudiante,compact('periodo','asignaturas','estudiante','matriculasPorPeriodo'))->render();

      $hora=new Carbon();
      $vista= \View::make($vistaurlReporte,compact('string','hora'))->render();

       return $this->crearPdf($vista);
    } 

    public function crearReporteGeneral($periodo,$programa){
      $periodo=Periodoacademico::find($periodo);
      $programa=Programaacademico::find($programa);
      $cantidadEstudiantesMatriculados=$this->cantidadEstudiantesMatriculados($periodo,$programa);
      $cantidadAsignaturas=$this->cantidadAsignaturas($periodo,$programa);
      $cantidadProfesores=$this->cantidadProfesores($periodo,$programa);
      $reprobadoMaterias=$this->reprobadoMaterias($periodo,$programa);

      $ponderado=['cantidadAsignaturas'=>$cantidadAsignaturas,'cantidadEstudiantesMatriculados'=>$cantidadEstudiantesMatriculados,'cantidadProfesores'=>$cantidadProfesores,'reprobadoMaterias'=>$reprobadoMaterias];

      $vistaurlGeneral='admin.informes.partes.reporteGeneral';
      $vistaurlReporte="admin.informes.partes.reportePrincipal";
      $string=\View::make($vistaurlGeneral,compact('periodo','programa','ponderado'))->render();

      $hora=new Carbon();
      $vista= \View::make($vistaurlReporte,compact('string','hora'))->render();

       return $this->crearPdf($vista);

    }

    public function cantidadEstudiantesMatriculados($periodo,$programa){
      $cantidad=0;
      $estudiantes= Estudiante::where('id_programaAcademico','=',$programa->CodigoPrograma)->get();

      foreach($estudiantes as $estudiante){
        foreach($estudiante->matriculas as $matriculas){
          if($matriculas->horario->periodoAcademico->Id == $periodo->Id){

             $cantidad= $cantidad+1;
          }
        }
      }
      //dd($cantidad);
      return $cantidad;
    }


    public function cantidadAsignaturas($periodo,$programa){
      
      $horario=Horario::Asignaturas($programa->Id)->Periodo($periodo->Id)->get();

      return count($horario); 
    }

    public function cantidadProfesores($periodo,$programa){
      $profesores = Horario::distinct()
            ->join('programaacademico_asignatura', 'horario.AsignaturaId' ,'=' ,'programaacademico_asignatura.Id')
            ->join('programaacademico', 'programaacademico_asignatura.programaacademicoId', '=' ,'programaacademico.Id')
            ->join('usuario','horario.UsuarioID','=','usuario.Id')
            ->select('programaacademico.Id as idprograma','usuario.Id' ,'usuario.Nombre','usuario.Apellidos','programaacademico.NombrePrograma')
            ->where('horario.PeriodoAcademicoId','like',$periodo->Id."%")->where('programaacademico_asignatura.programaacademicoId','like',$programa->Id."%")->get();

      return count($profesores);
    }

    public function reprobadoMaterias($periodo,$programa){
      /*$matriculas= Matricula::where('definitiva','<',3)->get();*/
      $cantidadReprobados=['cantidadReprobados'=>0,'RcantidadReprobados'=>0];
      $horarios=Horario::Asignaturas($programa->Id)->Periodo($periodo->Id)->get();
      
      foreach ($horarios as $horario) {
          foreach ($horario->matriculas as $matricula ) {
                if($matricula->definitiva < 3){
                  $cantidadReprobados['cantidadReprobados']=$cantidadReprobados['cantidadReprobados']+1;
                }
                if($matricula->tipoMatricula != 'N'){
                 $cantidadReprobados['RcantidadReprobados']=$cantidadReprobados['RcantidadReprobados']+1; 
                }

          }
              
      }

      return $cantidadReprobados;      
    }
      
}
