<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Inscrit extends Model implements Searchable
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nom', 'prenom', 'genre', 'date_naissance', 'rue', 'numero', 'boite', 'email', 'tel', 'gsm', 'commentaire_inscrit', 'prospect', 'newsletter'
    ];

    protected $casts = [
        'date_naissance' => 'datetime',
    ];

    public function ville()
    {
        return $this->belongsTo(Villes::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_inscrit');
    }

    public function recrutements()
    {
        return $this->belongsToMany(Recrutement::class, 'inscrit_recrutement')->withPivot('date_ajout', 'selection');
    }

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'inscrit_formation');
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = url("#/inscrits/show/{$this->id}");

        return new SearchResult($this, $this->nom . ' ' . $this->prenom, $url);
    }
}
