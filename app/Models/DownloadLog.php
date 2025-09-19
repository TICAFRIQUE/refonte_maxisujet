<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = ['user_id', 'sujet_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sujet()
    {
        return $this->belongsTo(Sujet::class);
    }
}
