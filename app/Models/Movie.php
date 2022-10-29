<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;


    public function AddMovie($request, $userId)
    {
        $this->name = $request->name;
        $this->year = $request->year;
        $this->genre = $request->genre;
        $this->user_id = $userId;
        $this->save();
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}