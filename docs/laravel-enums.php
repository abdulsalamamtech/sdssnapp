<?php

// app/Enums/UserRole.php
namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrator';
    case Refinery = 'refinery';
    case Marketer = 'marketer';
    case Transporter = 'transporter';
    case Driver = 'driver';
}

// app/Enums/AssetType.php
namespace App\Enums;

enum AssetType: string
{
    case Image = 'image';
    case Video = 'video';
    case File = 'file';
}

// app/Enums/HostingProvider.php
namespace App\Enums;

enum HostingProvider: string
{
    case AWS = 'AWS';
    case Cloudinary = 'cloudinary';
    case ImageKit = 'ImageKit';
}

// app/Enums/Status.php
namespace App\Enums;

enum Status: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}

// app/Enums/Currency.php
namespace App\Enums;

enum Currency: string
{
    case NGN = 'NGN';
    case USD = 'USD';
}

// app/Enums/TransactionType.php
namespace App\Enums;

enum TransactionType: string
{
    case Credit = 'credit';
    case Debit = 'debit';
}

// app/Enums/TransactionStatus.php
namespace App\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
}




Basic Enum Usage
// Using the UserRole enum we created
public function someMethod(UserRole $role) 
{
    // Compare enum values
    if ($role === UserRole::Administrator) {
        // Do something for admin
    }
    
    // Get the string value
    $roleString = $role->value; // 'administrator'
    
    // Get enum case name
    $caseName = $role->name; // 'Administrator'
}



In Database Models
// In your User model
protected $casts = [
    'role' => UserRole::class
];

// Now you can do:
$user = User::first();
if ($user->role === UserRole::Administrator) {
    // Handle admin logic
}



Validation Rules
use App\Enums\UserRole;

public function store(Request $request)
{
    $request->validate([
        'role' => ['required', Rule::enum(UserRole::class)]
    ]);
}



Getting All Cases
// Get all cases
$roles = UserRole::cases();

// Get all values
$roleValues = collect(UserRole::cases())->map(fn ($case) => $case->value);



Adding Methods to Enums

enum UserRole: string
{
    case Administrator = 'administrator';
    case Refinery = 'refinery';
    case Marketer = 'marketer';
    
    public function getPermissions(): array
    {
        return match($this) {
            self::Administrator => ['all'],
            self::Refinery => ['manage-products', 'approve-purchases'],
            self::Marketer => ['create-purchases', 'view-products'],
        };
    }
    
    public function label(): string
    {
        return match($this) {
            self::Administrator => 'System Administrator',
            self::Refinery => 'Refinery Manager',
            self::Marketer => 'Marketing Agent',
        };
    }
}

// Using the methods
$role = UserRole::Refinery;
$permissions = $role->getPermissions(); // ['manage-products', 'approve-purchases']
$label = $role->label(); // 'Refinery Manager



In Blade Views
<select name="role">
    @foreach(UserRole::cases() as $role)
        <option value="{{ $role->value }}">{{ $role->label() }}</option>
    @endforeach
</select>




Using with API Resources
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => [
                'value' => $this->role->value,
                'label' => $this->role->label(),
                'permissions' => $this->role->getPermissions(),
            ]
        ];
    }
}







In Route Middleware
Route::middleware(['auth:sanctum', function ($request, $next) {
    if ($request->user()->role !== UserRole::Administrator) {
        abort(403, 'Unauthorized action.');
    }
    return $next($request);
}])->group(function () {
    // Admin routes here
});





With Form Requests
class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'role' => ['required', Rule::enum(UserRole::class)],
            'status' => ['required', Rule::enum(Status::class)],
        ];
    }
}





With Factory States

class UserFactory extends Factory
{
    public function administrator()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => UserRole::Administrator,
            ];
        });
    }
}

// Usage in tests
$admin = User::factory()->administrator()->create();




Best Practices:

Always type-hint enum parameters in methods
Use enums instead of magic strings
Add helper methods to make enums more useful
Cast enum fields in your models
Use Rule::enum() for validation
Document your enum cases and methods

Remember that enums are available from PHP 8.1 onwards, so make sure your PHP version is compatible. 
They provide type safety and help prevent bugs caused by invalid string values 
being used for statuses, roles, and other predefined sets of values.