<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Show all users with admin/judge capabilities
     */
    public function index()
    {
        $admins = User::where('is_admin', true)->get();
        $judges = User::where('is_judge', true)->get();
        
        return view('admin.users.index', compact('admins', 'judges'));
    }
    
    /**
     * Show form to add a new admin/judge
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a new admin/judge
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users|alpha_dash',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,judge',
            'bio' => 'nullable|string'
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->bio = $request->bio;
        
        if ($request->role === 'admin') {
            $user->is_admin = true;
        } elseif ($request->role === 'judge') {
            $user->is_judge = true;
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }
    
    /**
     * Show form to edit an admin/judge
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update an admin/judge
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,judge,user',
            'bio' => 'nullable|string'
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->bio = $request->bio;
        
        // Update roles
        $user->is_admin = ($request->role === 'admin');
        $user->is_judge = ($request->role === 'judge');
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }
    
    /**
     * Remove admin/judge privileges
     */
    public function removeRole($id)
    {
        $user = User::findOrFail($id);
        
        $user->is_admin = false;
        $user->is_judge = false;
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'تم إزالة الصلاحيات بنجاح');
    }
}