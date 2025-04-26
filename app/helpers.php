<?php

use App\Jobs\SendFCMNotificationJob;
use App\Models\User;
use App\Notifications\AdminMessage;
use App\Notifications\UserMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


function sendNotificationToAdmin($title, $massge, $title_en = '', $massge_en = '', $key = null, $keyID = null)
{
    $user = User::where('type', 'admin')->first();
    Notification::send([$user], new AdminMessage($massge, $title, $massge_en, $title_en, $key, $keyID));
}
function sendNotificationToUser($title, $massge, $title_en, $massge_en, $user, $key = null, $keyId = null)
{
    Notification::send([$user], new UserMessage($massge, $title, $massge_en, $title_en, $key, $keyId));
    SendFCMNotificationJob::dispatch($user->fcm, $title_en, $massge_en, []);
}

// Add this helper method to your controller
function convertHexToRGB($hex)
{
    // Remove the hash if it exists
    $hex = str_replace('#', '', $hex);

    // Convert shorthand hex to full hex if necessary
    if (strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    // Convert hex to RGB values
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Return RGB format
    return "rgb($r, $g, $b)";
}

// Add this method to convert RGB back to HEX for the edit form
function convertRGBToHex($rgb)
{
    // Check if the color is in RGB format
    if (strpos($rgb, 'rgb') !== false) {
        // Extract RGB values
        preg_match('/rgb$$(\d+),\s*(\d+),\s*(\d+)$$/', $rgb, $matches);

        if (count($matches) == 4) {
            $r = dechex($matches[1]);
            $g = dechex($matches[2]);
            $b = dechex($matches[3]);

            // Ensure two digits for each color
            $r = strlen($r) == 1 ? '0' . $r : $r;
            $g = strlen($g) == 1 ? '0' . $g : $g;
            $b = strlen($b) == 1 ? '0' . $b : $b;

            return '#' . $r . $g . $b;
        }
    }

    return $rgb; // Return original value if not RGB
}

// function sendNotificationToAdmin($title, $massge, $title_en = '', $massge_en = '', $key = null, $keyID = null)
// {
//     $user = User::where('type', 'admin')->first();
//     Notification::send([$user], new AdminMessage($massge, $title, $massge_en, $title_en, $key, $keyID));
// }
// function sendNotificationToUser($title, $massge, $title_en, $massge_en, $user, $key = null, $keyId = null)
// {
//     Notification::send([$user], new UserMessage($massge, $title, $massge_en, $title_en, $key, $keyId));
//     SendFCMNotificationJob::dispatch($user->fcm, $title_en, $massge_en);
// }

function extractCountryName($fullAddress)
{
    // Use a regular expression to match the last occurrence of a country name
    if (preg_match('/\b(\w+\s*)+\b$/', $fullAddress, $matches)) {
        // $matches[0] contains the matched country name
        $countryName = trim($matches[0]);

        // You may need to further process $countryName to remove any extra characters or formatting
        return $countryName;
    }

    // If no country name is found, return a default value or handle it accordingly
    return "Country not found";
}


function userPointsUpdate(User $user, int $count)
{
    $currentDate = Carbon::now()->startOfDay();
    $user->points += $count;
    $user->played_at = $currentDate;
    $user->save();
}

function generateUniqueInvitationCode()
{
    // Generate a unique random code, for example, a combination of letters and numbers
    $code = generateUniqueCode();

    // Check if the generated code already exists in the database
    while (User::where('invitation_code', $code)->exists()) {
        $code = generateUniqueCode();
    }

    return $code;
}
function generateUniqueCode()
{
    // Generate a unique random code, for example, a combination of letters and numbers
    $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));

    return $code;
}


function buildPaginatedResponse($products)
{
    return [
        'current_page' => $products->currentPage(),
        'data' => $products->items(),
        'first_page_url' => $products->url(1),
        'from' => $products->firstItem(),
        'last_page' => $products->lastPage(),
        'last_page_url' => $products->url($products->lastPage()),
        'next_page_url' => $products->nextPageUrl(),
        'path' => $products->path(),
        'per_page' => $products->perPage(),
        'prev_page_url' => $products->previousPageUrl(),
        'to' => $products->lastItem(),
        'total' => $products->total(),
    ];
}


function successmMssageResponse($message = 'Success', $statusCode = 200, $data = [])
{
    return response()->json([
        'data' => $data,
        'message' => $message,
        'status_code' => $statusCode,
    ], $statusCode);
}

function successResponse($data = [], $statusCode = 200, $message = 'Success')
{
    return response()->json([
        'data' => $data,
        'message' => $message,
        'status_code' => $statusCode,
    ], $statusCode);
}

function errorResponse($message, $statusCode = 500, $error = [])
{
    return response()->json(['message' => $message, 'status_code' => $statusCode, 'errors' => $error], $statusCode);
}



function convertCurrency($amount)
{
    // // amount = 100
    // $fromCurrency = env("Default_CURRENCY"); // 1
    // $toCurrency =  Session::get('currency'); // 10
    // $fromRate = Country::where('code', $fromCurrency)->first()->exchange_rate; // 1
    // $toRate = Country::where('code', $toCurrency)->first()->exchange_rate; // 10

    // $convertedAmount = ($amount / $fromRate) * $toRate;  // 100 / 1 * 31 = 3100

    return $amount;
}


function nameField($field, $alias)
{
    return DB::raw("$field" . app()->getLocale() . " AS " . $alias);
}
function sendOtpWhatsApp($body, $recieveNumber = "+201113051656")
{
    try {
        $response = Http::post('https://api.ultramsg.com/instance64835/messages/chat', [
            'token' => '1jeqxe85deo79r2a',
            'to' => $recieveNumber,
            'body' => $body
        ]);

        return $response->body();
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function generateOtp($identifier)
{
    $otp = new Otp;
    $otpCode = $otp->generate($identifier, 4, 60);
    return (string) $otpCode->token;
}
function setEnvironmentValue($envKey, $envValue)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    $oldValue = env($envKey);

    if ($oldValue === false) {
        // Key does not exist in the .env file, so we append it
        $str .= "\n{$envKey}={$envValue}\n";
    } else {
        // Key exists, so we replace it
        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
    }

    $fp = fopen($envFile, 'w');
    fwrite($fp, $str);
    fclose($fp);
}


function statusInvitationCode($userPresent, $invitation_code)
{
    if ($userPresent->is_used) {
        return false;
        // return response()->json(['message' => __('custom.registered_invitation'), 'status_code' => 404,], 404);
    }

    $user = User::where('invitation_code', $invitation_code)->first();

    if (!$user) {
        return false;
        //  response()->json(['message' => 'رمز الدعوة غير صحيح.', 'status_code' => 404,], 404);
    }

    $userPresent->is_used = true;
    $userPresent->save();
    // userPointsUpdate($user, 100);
    return true;
    //response()->json(['message' => 'تم الدعوه بنجاح', 'status_code' => 200,], 200);
}
