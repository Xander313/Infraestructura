<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Model;
use App\Models\Audit\AuditFinding; // <- importar el modelo de hallazgo
use App\Models\IAM\AppUser;       // <- importar el modelo de usuario

class CorrectiveAction extends Model
{
    // Tabla real en tu base de datos
    protected $table = 'audit.corrective_action'; 

    // Clave primaria real
    protected $primaryKey = 'ca_id';

    // Si tu tabla no tiene created_at / updated_at
    public $timestamps = false;

    // Mass assignment
    protected $fillable = [
        'finding_id',
        'owner_user_id',
        'due_at',
        'status',
        'closed_at',
        'outcome'
    ];

    // Relación con Hallazgo
    public function finding() {
        return $this->belongsTo(AuditFinding::class, 'finding_id', 'finding_id');
    }

    // Relación con Usuario (Propietario)
    public function owner() {
        return $this->belongsTo(\App\Models\IAM\AppUser::class, 'owner_user_id', 'user_id');
    }    
}
