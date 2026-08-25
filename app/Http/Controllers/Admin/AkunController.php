<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search nama/email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->orderByRaw("FIELD(role, 'admin', 'guru', 'siswa', 'industri')")
            ->orderBy('name')
            ->get();

        return view('admin.akun.index', compact('users'));
    }

    /**
     * Kembalikan password asli (dari salinan terenkripsi) untuk ditampilkan admin.
     */
    public function showPassword(User $user)
    {
        return response()->json([
            'password' => $user->getReadablePassword(),
        ]);
    }
}
