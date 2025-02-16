<?php

// database/migrations/2024_01_10_000001_create_roles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('roles', function (Blueprint $table) {
//             $table->id();
//             $table->string('name');
//             $table->text('description')->nullable();
//             $table->timestamps();
//             $table->softDeletes();
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('roles');
//     }
// };

// database/migrations/2024_01_10_000002_create_users_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['administrator', 'refinery', 'marketer', 'transporter', 'driver']);
            $table->string('phone_number');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

// database/migrations/2024_01_10_000003_create_user_roles_table.php
// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('user_roles', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('user_id')->constrained()->onDelete('cascade');
//             $table->foreignId('role_id')->constrained()->onDelete('cascade');
//             $table->timestamps();
//             $table->softDeletes();
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('user_roles');
//     }
// };



// database/migrations/2024_01_10_000005_create_assets_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('file_id');
            $table->enum('type', ['image', 'video', 'file'])->nullable();
            $table->string('url');
            $table->string('path')->nullable();
            $table->text('description')->nullable();
            $table->integer('size');
            $table->enum('hosted_at', ['AWS', 'cloudinary', 'ImageKit']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};



// database/migrations/2024_01_10_000004_create_activities_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('logs')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};



// database/migrations/2024_01_10_000007_create_admin_departments_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->string('role');
            $table->text('responsibility_description')->nullable();
            $table->string('zone')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_departments');
    }
};

// database/migrations/2024_01_10_000006_create_refineries_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('refineries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('license_details')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('refineries');
    }
};


// database/migrations/2024_01_10_000007_create_refinery_departments_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('refinery_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->string('role');
            $table->text('responsibility_description')->nullable();
            $table->string('zone')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('refinery_departments');
    }
};




// database/migrations/2024_01_10_000017_create_currency_types_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('currency_types', function (Blueprint $table) {
            $table->id();
            // - name (naira | dollar)
            // 'sign' => '₦',
            // 'name' or 'word' => 'Naira', 
            // 'code' => 'NGN',
            $table->string('name', 32)->unique()->comment('naira');
            $table->string('sign', 32)->nullable()->comment('₦');
            $table->string('code', 32)->nullable()->comment('NGN');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();          
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_types');
    }
};


// database/migrations/2024_01_10_000017_create_exchange_rates_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            // - name (naira | dollar)
            // - exchange_rate
            $table->string('name', 32);
            $table->decimal('rate', 15, 2);
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
};




// Continue with more migrations...
// Add migrations for marketers, transporters, drivers, trucks, virtual_accounts, etc.
// For brevity, I'll stop here but would continue with all tables in the schema



// database/migrations/2024_01_10_000008_create_marketers_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('license_details');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketers');
    }
};



// database/migrations/2024_01_10_000009_create_marketer_departments_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('marketer_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->string('role');
            $table->text('responsibility_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketer_departments');
    }
};


// database/migrations/2024_01_10_000018_create_marketer_accounts_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('marketer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->enum('accounts_type', ['dprp', 'dogcl'])->default('dprp');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketer_accounts');
    }
};



// database/migrations/2024_01_10_000015_create_marketer_account_transactions_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('marketer_account_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->foreignId('marketer_account_id')->constrained('marketer_accounts')->onDelete('cascade');
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

};



// database/migrations/2024_01_10_000010_create_transporters_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('transporters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('license_details')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transporters');
    }
};



// database/migrations/2024_01_10_000011_create_transporter_departments_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('transporter_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained('transporters')->onDelete('cascade');
            $table->string('role');
            $table->text('responsibility_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transporter_departments');
    }
};


// database/migrations/2024_01_10_000012_create_customers_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->unique();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};


// database/migrations/2024_01_10_000012_create_drivers_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained('transporters')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();
            $table->string('license_number')->unique();
            $table->string('license_details');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->enum('movement_status', ['pending', 'assigned'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};

// database/migrations/2024_01_10_000013_create_trucks_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('truck_number')->unique();
            $table->decimal('quantity', 10, 2);
            $table->integer('compartment')->default(3);
            $table->decimal('calibrate_one', 10, 2)->nullable();
            $table->decimal('calibrate_two', 10, 2)->nullable();
            $table->decimal('calibrate_three', 10, 2)->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->enum('movement_status', ['pending', 'assigned'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trucks');
    }
};



// database/migrations/2024_01_10_000014_create_virtual_accounts_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('belongs_to', ['marketer', 'transporter', 'driver']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('bank');
            $table->string('account_number')->unique();
            $table->enum('currency', ['USD', 'NGN'])->default('NGN');
            $table->decimal('daily_limit', 15, 2)->nullable();
            $table->decimal('monthly_limit', 15, 2)->nullable();
            $table->string('security_pin', 4)->default('1234');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketer_virtual_accounts');
    }
};


// database/migrations/2024_01_10_000015_create_virtual_account_transactions_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('virtual_account_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_account_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('virtual_account_transactions');
    }
};


// This is not important
// database/migrations/2024_01_10_000016_create_payment_accounts_table.php
// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('payment_accounts', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('user_id')->constrained()->onDelete('cascade');
//             $table->string('virtual_payment_account');
//             $table->decimal('amount', 15, 2)->default(0);
//             $table->timestamps();
//             $table->softDeletes();
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('payment_accounts');
//     }
// };






// database/migrations/2024_01_10_000019_create_product_types_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade')->comment('added by system administrator');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_types');
    }
};

// database/migrations/2024_01_10_000020_create_products_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'active'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};


// database/migrations/2024_01_10_000021_create_purchases_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->decimal('liters', 15, 2);

            $table->decimal('amount', 15, 2);
            $table->string('pfi_number')->unique();
            $table->text('comment')->nullable();


            // After purchase
            $table->decimal('price_per_liter', 15, 2)->comment('this price is gotten from the refinery product price in default currency');
            // use Carbon\Carbon;
            // $now = Carbon::now();
            // $yesterday = Carbon::yesterday();
            // 'expires' => Carbon::now()->addYear()->toDateTimeString()
            $table->date('purchase_expires_at')->nullable()->comment('this is set by the refinery');

            $table->enum('status', ['pending', 'approve', 'reject'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('the refinery user id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};


// database/migrations/2024_01_10_000022_create_purchase_messages_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->text('comment_by_refinery')->nullable();
            $table->text('comment_by_marketer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_messages');
    }
};

// database/migrations/2024_01_10_000023_create_purchase_payment_proofs_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('reference_number');
            $table->decimal('amount', 15, 2);
            $table->enum('currency', ['NGN', 'USD'])->default('NGN');
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('comment')->nullable();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_payment_proofs');
    }
};


// database/migrations/2024_01_10_000024_create_programs_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            // For Refinery Record purposes
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('atc_number')->nullable()->unique();
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null')->comment('the refinery user id');
            $table->text('comment')->nullable();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
};

// database/migrations/2024_01_10_000025_create_program_messages_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('program_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('refinery_id')->constrained('refineries')->onDelete('cascade');
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->text('comment_by_refinery')->nullable();
            $table->text('comment_by_marketer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_messages');
    }
};

// database/migrations/2024_01_10_000026_create_program_trucks_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('program_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('truck_id')->constrained('trucks')->onDelete('cascade');
            $table->decimal('liters', 15, 2);
            $table->enum('status', ['pending', 'moving', 'delivered'])->default('pending');
            $table->decimal('liters_lifted', 15, 2)->nullable();
            $table->string('meter_ticket_number')->nullable();
            $table->string('waybill_number')->nullable();
            
            // Customer details
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->enum('customer_status', ['pending', 'delivered'])->default('pending');
            $table->enum('driver_status', ['pending', 'delivered'])->default('pending');

            // $table->string('customer_name')->nullable();
            // $table->string('customer_phone_number')->nullable();
            // $table->text('address')->nullable();
            // $table->string('city')->nullable(); // LGA
            // $table->string('state')->nullable();
            // $table->string('country')->default('Nigeria');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_trucks');
    }
};

// database/migrations/2024_01_10_000027_create_locations_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->nullOnDelete();
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};