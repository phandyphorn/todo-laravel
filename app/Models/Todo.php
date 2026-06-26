<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// php artisan make:model Todo (we get it by run this command in terminal, it will create this file (Model) and migration file for us)
class Todo extends Model
{
    use HasFactory;

    // Only these fields CAN be mass-assigned
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

     // Relationship: A todo belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
