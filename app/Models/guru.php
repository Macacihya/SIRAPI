<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['sekolah_id', 'nama', 'nip', 'mata_pelajaran'];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
?>