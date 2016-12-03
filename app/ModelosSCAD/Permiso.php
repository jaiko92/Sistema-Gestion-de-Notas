<?php

namespace App\ModelosSCAD;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permiso
 */
class Permiso extends Model
{
    protected $table = 'permisos';
    protected $connection = 'docentes';

    protected $primaryKey = 'Id';

	public $timestamps = false;

    protected $fillable = [
        'Nombre'
    ];

    protected $guarded = [];

        
}