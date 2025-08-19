<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function index()
    {
        $work_teams = WorkTeam::orderBy('id', 'asc')->get();
        return view('administrator.users', compact('work_teams'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'tel' => 'nullable|digits:10',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'nullable|string|min:8',
            'rol' => 'required|in:technical_support,administrator,operator',
            'work_team_id' => 'nullable',
            'expires' => 'nullable|date|after_or_equal:today',
            'created' => 'nullable|date', // Validación como fecha
            'is_active' => 'boolean',
            'observations' => 'nullable|string',
            'picture_upload' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Manejar la subida de imagen
        if ($request->hasFile('picture_upload')) {
            $validated['profile_photo_path'] = $request->file('picture_upload')->store('users', 'public');
        }

        // Generar la contraseña si no se proporciona
      
        $validated['password'] = bcrypt($validated['username']); // Contraseña por defecto basada en el username
  

        // Crear el usuario
        $user =User::create($validated);
        $user->created_at = $validated['created'] ?? now(); 
        $user->save();
       
        $user->assignRole($request->input('rol'));

        return redirect()->route('user.admin')->with('success', 'Elemento creado correctamente.');
    }


    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
        ]);
    
        $user = User::findOrFail($request->id);
    
    
        $user['password'] = bcrypt($user['username']);
        $user->save();

      
        return redirect()->route('user.admin')->with('success',   "La contraseña se ha restablecido exitosamente para <br> Nombre: ".$user->name." ".$user->last_name." ".$user->second_last_name."<br>"."Usuario: ".$user->username."<br>"."Nueva contraseña: ".$user->username."<br>"."El cambio se aplicara a partir del próximo inicio de sesión.");
    }
    

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'username' => 'required|string|unique:users,username,'.$request->id .'|max:255',
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'tel' => 'nullable|digits:10',
            'email' => 'required|email|unique:users,email,' . $request->id . '|max:255',
            'password' => 'nullable|string|min:8',
            'rol' => 'required|in:technical_support,administrator,operator',
            'work_team_id' => 'nullable',
            'expires' => 'nullable|date|after_or_equal:today',
            'created' => 'nullable|date', // Validación como fecha
            'is_active' => 'boolean',
            'observations' => 'nullable|string',
            'picture_upload' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
    
        $user = User::findOrFail($request->id);
    
        if ($request->hasFile('picture_upload')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('picture_upload')->store('users', 'public');
        }
    
        $user->fill($validated);
        $user->created_at = $validated['created'] ?? $user->created_at; // Actualizar el campo created_at
        $user->save();
    
        $user->syncRoles([$request->input('rol')]);
        return redirect()->route('user.admin')->with('success', 'Elemento actualizado correctamente.');
    }




    
    public function updatePassword(Request $request)
    {
  // Validar los datos del formulario
       // Validar los datos del formulario
    $validated = $request->validate([
        'password' => [
            'required',
            'string',
            'min:6',
            'max:15',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).+$/',
        ],
        'repeat_password' => 'required|same:password',
    ]);

        $user = User::find(Auth::user()->id);
     
        $user['password'] = bcrypt($validated['password']); // Contraseña por defecto basada en el username
  
        $user->save();
      

        Auth::logout();
        return redirect()->route('login')->withErrors(['message' => 'El cambio de contraseña se ha realizado exitosamente, ingrese nuevamente.']);
    }

    

    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect()->back()->withErrors(['user' => 'El usuario ya no existe.']);
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('user.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new User();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }
}
