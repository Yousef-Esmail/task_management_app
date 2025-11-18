<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'completed',
        'user_id'
        ];
        public function getPriorityAttribute($value)
        {
            $map = [
                1 => 'High',
                2 => 'Medium',
                3 => 'Low',
            ];
            return $map[$value] ?? 'Medium';
        }
        public function getCompletedAttribute($value)
        {
            return $value ? 'Completed' : 'Pending';
        }
        public function user()
        {
            return $this->belongsTo(User::class);
        }
}