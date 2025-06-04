

```php
    use Illuminate\Support\Str;
    $random = Str::random(40);


    strtoupper(uniqid('OR')) // OR stands for Orders prefix.


    $timestamp = Carbon::now()->timestamp;
    $ref_id = Str::random(18);
    $random_string = Str::random(32);
    $combine = md5($timestamp . $ref_id . $random_string);
    $unique_reference = uniqid($combine);
    $transactionReference = Str::uuid($unique_reference);


    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Trainer::where('code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

```