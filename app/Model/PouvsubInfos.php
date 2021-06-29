<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\Formation;
use App\Model\Pouvsub;


class PouvsubInfos extends Model
{
    protected $table = 'pouvsub_infos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'age', 'num_national', 'statut_legal', 'diplome', 'duree_chomage', 'moyen_recrutement', 'groupe_social',
    ];

    public $timestamps = false;

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function pouvsub()
    {
        return $this->belongsTo(Pouvsub::class);
    }

    public function infos()
    {
        return $this->hasMany(InscritInfos::class, 'pouvsub_info_id');
    }
}
