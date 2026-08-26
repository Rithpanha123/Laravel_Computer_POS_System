<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

       // ស្វែងរក User
$user = User::where('username', trim($request->username))
            ->orWhere('email', trim($request->username))
            ->first();

if (!$user) {
    return response()->json([
        'success' => false,
        'message' => 'រកមិនឃើញគណនីនេះទេ!'
    ], 401);
}

$inputPassword = (string)$request->password;
$dbPassword = (string)($user->password_hash ?? $user->password);

// ពិនិត្យមើលតាមរយៈ Hash::check ឬប្រៀបធៀបផ្ទាល់ (ករណីមិនទាន់ Hash)
$isPasswordCorrect = false;

if (Hash::check($inputPassword, $dbPassword)) {
    $isPasswordCorrect = true;
} elseif ($inputPassword === $dbPassword) {
    // ប្រសិនបើក្នុង DB ជា Plain text ធម្មតា
    $isPasswordCorrect = true;
    // Auto Hash ទុកសម្រាប់លើកក្រោយ
    $user->password_hash = Hash::make($inputPassword);
    $user->save();
}

if (!$isPasswordCorrect) {
    return response()->json([
        'success' => false,
        'message' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវឡើយ!'
    ], 401);
}

if (isset($user->is_active) && !$user->is_active) {
    return response()->json([
        'success' => false,
        'message' => 'គណនីនេះត្រូវបានផ្អាកដំណើរការ!'
    ], 403);
}

// បង្កើត Token
$token = $user->createToken('pos-mobile-token')->plainTextToken;

return response()->json([
    'success' => true,
    'message' => 'ចូលប្រព័ន្ធជោគជ័យ!',
    'token'   => $token,
    'user'    => [
        'id'        => $user->user_id ?? $user->id,
        'username'  => $user->username,
        'full_name' => $user->full_name ?? $user->username,
    ]
], 200);
    }
}