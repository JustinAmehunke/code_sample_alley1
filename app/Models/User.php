<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $table = 'tbl_users';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'mobile',
        'email',
        'password',
        'auth_code',
        'tbl_designations_id',
        'tbl_departments_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function designation(){
        return $this->belongsTo(Designation::class, 'tbl_designations_id', 'id');
    }
    public function department(){
        return $this->belongsTo(Department::class, 'tbl_departments_id', 'id');
    }

    public function reports_to()
    {
        return $this->belongsTo(User::class, 'reports_to', 'id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id', 'id');
    }

    public function team_leader()
    {
        return $this->belongsTo(User::class, 'team_leader_id', 'id');
    }
}
