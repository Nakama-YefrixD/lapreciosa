<?php

namespace App\Http\Controllers\panel\Configuraciones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\User;
use App\Control;
use DB;

class usuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('configuracion.usuarios.index');
    }

    public function tb_usuarios()
    {
        $usuarios = User::get([
                                'users.id as idUsuario',
                                'users.name as nombreUsuario',
                                'users.email as emailUsuario',
                                'users.imagen as imagenUsuario'
                            ]);
        
        return Datatables::of($usuarios)->make(true);
    }

    public function usuarioEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $usuarios = User::find($request['idUsuarioEditar']);
            $usuarios->name = $request['nombreUsuarioEditar'];
            $usuarios->email = $request['emailUsuarioEditar'];

            if($request['datosUsuarioEditar'] == 1){
                $pass = $request['contrasenaUsuarioEditar'];
                $usuarios->password = Hash::make($pass);
            }
            
            if($usuarios->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "Users";
                $control->campos = "name, email, password";
                $control->datos = $request['idUsuarioEditar'].', '. $request['nombreUsuarioEditar'].', '. $request['emailUsuarioEditar'];
                $control->descripcion = "Editar un usuario";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }
    
    public function usuarioCrear(Request $request)
    {

        DB::beginTransaction();
        try {
            
            $usuario = new User;
            $usuario->name = $request['nombreUsuarioCrear']; 
            $usuario->email = $request['emailUsuarioCrear'];
            $usuario->password = Hash::make($request['contrasenaUsuarioCrear']);
            $usuario->imagen = 'usuarioHombre.png'; 
            
            if($usuario->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Crear";
                $control->tabla = "Users";
                $control->campos = "all";
                $control->datos = $request['nombreUsuarioCrear'].', '. $request['emailUsuarioCrear'].', '. $request['contrasenaUsuarioCrear'];
                $control->descripcion = "Crear un usuario";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

}
