<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'city',
        'state',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function refinery()
    {
        return $this->hasOne(Refinery::class);
    }
}

// app/Models/Role.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}

// app/Models/Activity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'role',
        'description',
        'logs',
    ];

    protected $casts = [
        'logs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Asset.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'file_id',
        'type',
        'url',
        'path',
        'description',
        'size',
        'hosted_at',
    ];
}

// Continue with more models...
// Add models for Refinery, RefineryDepartment, Marketer, Transporter, etc.


// app/Models/Refinery.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refinery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_details',
        'description',
        'status',
    ];

    protected $casts = [
        'license_details' => 'array',
        'status' => \App\Enums\Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departments()
    {
        return $this->hasMany(RefineryDepartment::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}

// app/Models/RefineryDepartment.php
namespace App\Models;

class RefineryDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'refinery_id',
        'role',
        'responsibilities_description',
        'zone',
        'state',
    ];

    public function refinery()
    {
        return $this->belongsTo(Refinery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Marketer.php
namespace App\Models;

class Marketer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_details',
        'status',
    ];

    protected $casts = [
        'license_details' => 'array',
        'status' => \App\Enums\Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departments()
    {
        return $this->hasMany(MarketerDepartment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function virtualAccounts()
    {
        return $this->hasMany(VirtualAccount::class);
    }

    public function marketerAccounts()
    {
        return $this->hasMany(MarketerAccount::class);
    }
}

// app/Models/MarketerDepartment.php
namespace App\Models;

class MarketerDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'marketer_id',
        'role',
        'responsibilities_description',
    ];

    public function marketer()
    {
        return $this->belongsTo(Marketer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Transporter.php
namespace App\Models;

class Transporter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_details',
        'status',
    ];

    protected $casts = [
        'license_details' => 'array',
        'status' => \App\Enums\Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departments()
    {
        return $this->hasMany(TransporterDepartment::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'added_by', 'user_id');
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class, 'added_by', 'user_id');
    }
}

// app/Models/TransporterDepartment.php
namespace App\Models;

class TransporterDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transporter_id',
        'role',
        'responsibilities_description',
    ];

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Driver.php
namespace App\Models;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'added_by',
        'first_name',
        'last_name',
        'other_name',
        'email',
        'phone_number',
        'license_number',
        'license_details',
        'address',
        'state',
        'country',
        'status',
        'movement_status',
    ];

    protected $casts = [
        'license_details' => 'array',
        'status' => \App\Enums\Status::class,
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

// app/Models/Truck.php
namespace App\Models;

class Truck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_id',
        'added_by',
        'name',
        'description',
        'truck_number',
        'quantity',
        'compartment',
        'calibrate_one',
        'calibrate_two',
        'calibrate_three',
        'status',
        'movement_status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'calibrate_one' => 'decimal:2',
        'calibrate_two' => 'decimal:2',
        'calibrate_three' => 'decimal:2',
        'status' => \App\Enums\Status::class,
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function programTrucks()
    {
        return $this->hasMany(ProgramTruck::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}

// app/Models/VirtualAccount.php
namespace App\Models;

class VirtualAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'marketer_id',
        'balance',
        'bank',
        'account_number',
        'currency',
        'daily_limit',
        'monthly_limit',
        'security_pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'monthly_limit' => 'decimal:2',
        'currency' => \App\Enums\Currency::class,
    ];

    protected $hidden = [
        'security_pin',
    ];

    public function marketer()
    {
        return $this->belongsTo(Marketer::class);
    }

    public function transactions()
    {
        return $this->hasMany(VirtualAccountTransaction::class);
    }
}

// app/Models/VirtualAccountTransaction.php
namespace App\Models;

class VirtualAccountTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'virtual_account_id',
        'transaction_type',
        'amount',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_type' => \App\Enums\TransactionType::class,
        'status' => \App\Enums\TransactionStatus::class,
    ];

    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }
}