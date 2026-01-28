<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Student;
use App\Models\School;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'school_id',
        'fcm_token',
        'device_type',
        'last_used_at'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Update or create device token with proper rules
     */
    public static function updateOrCreateToken(array $data)
    {
        return self::updateOrCreate(
            [
                'fcm_token' => $data['fcm_token'],
                'device_type' => $data['device_type'] ?? 'android'
            ],
            array_merge($data, [
                'last_used_at' => now()
            ])
        );
    }

    /**
     * Remove token for user logout
     */
    public static function removeToken(string $fcmToken, string $deviceType = 'android')
    {
        return self::where('fcm_token', $fcmToken)
            ->where('device_type', $deviceType)
            ->delete();
    }

    /**
     * Get tokens for student
     */
    public static function getStudentTokens(int $studentId, string $deviceType = 'android')
    {
        return self::where('student_id', $studentId)
            ->where('device_type', $deviceType)
            ->where('last_used_at', '>=', now()->subDays(30)) // Active tokens only
            ->pluck('fcm_token')
            ->toArray();
    }
}
