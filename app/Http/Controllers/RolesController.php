<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Http\Resources\RolesResource;
use App\Http\Requests\RolesIdFormRequest;
use App\Http\Requests\RolesFormRequest;
use DB;
use App\Models\EstadosProcesos;
use App\Models\ViewRolPermissions;
use App\Models\Permissions;
use App\Http\Resources\ViewRoleResource;
use App\Models\RoleHasPermissions;
use Spatie\Permission\Models\Role as RoleSpatie;



class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try 
        {
            $roles = Roles::all();
            if (!count($roles)) 
            {
            return response(['data' => '','code'=>204]);
            }
            return response(['data'=> RolesResource::collection($roles),'code' => 200]);
        }
        catch (\Exception $e) 
        {
            return response(['data'=> 'Error al cargar roles','code' => 500]);   
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolesFormRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $new_rol = Roles::create($request->all());
            DB::commit();
            return response(['data'=> $new_rol,'code' => 201]);

        } catch (Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al crear registro','code' => 500]);   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $roles = Roles::get(['id','name']);

        if (!count($roles)) 
        {
           return response(['data' => '','code'=>204]);   
        }
        return response(['data'=> $roles,'code' => 200]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(RolesFormRequest $request, $id)
    {
        try 
        {
            DB::beginTransaction();
            $edit_rol = Roles::findOrFail($id);
            $edit_rol->update($request->all());
            DB::commit();
            return response(['data'=> $edit_rol,'code' => 200]);

        } catch (\Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            DB::beginTransaction();
            $edit_rol = Roles::findOrFail($id);
            $estado = EstadosProcesos::where([['sts_inicial',$edit_rol->estado],['tabla','roles']])
            ->firstOrFail();
            $edit_rol->estado = $estado->sts_final;
            $edit_rol->save();
            DB::commit();
            return response(['data'=> $edit_rol,'code' => 200]);

        } catch (\Throwable $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al desactivar registro','code' => 500]);   
        }
    }

    public function rolesMenu($id)
    {
        $role_asigned = ViewRolPermissions::select('id','name','estado','asignado')
        ->where([['role_id',$id],['nivel_json','1']]);

        $roles = Permissions::select('id','name','estado',DB::raw('0 AS asignado'))
        ->whereNotIn('id', $role_asigned->pluck('id'))
        ->where('nivel_json','1')
        ->union($role_asigned)
        ->orderby('id')
        ->get();
        
        return response(['data'=> ViewRoleResource::collection($roles),'code' => 200]);
    }

    public function roleMenuButton($role,$id)
    {
        //modificar devolver desasignados
        $role_asigned = ViewRolPermissions::select('id','name','estado','asignado')
        ->where([['sub_btn',$id],['nivel_json','2'],['role_id',$role]]);

        $role = Permissions::select('id','name','estado',DB::raw('0 AS asignado'))
        ->whereNotIn('id', $role_asigned->pluck('id'))
        ->where([['sub_btn',$id],['nivel_json','2']])
        ->union($role_asigned)
        ->orderby('id')
        ->get();

        return response(['data'=> ViewRoleResource::collection($role),'code' => 200]);
    }

    public function roleMenuButtonAll($role,$id)
    {
        //posiblemente quitar
        $role_asigned = ViewRolPermissions::select('id','name','estado','asignado')
        ->where([['sub_btn',$id],['nivel_json','2'],['role_id',$role]]);

        $roles = Permissions::select('id','name','estado')
        ->whereNotIn('id', $role_asigned->pluck('id'))
        ->where([['sub_btn',$id],['nivel_json','2']])
        ->orderby('id')
        ->get();

        return response(['data'=> ViewRoleResource::collection($roles),'code' => 200]);
    }

    public function rolesAssign($role,$menu,$estado)
    {
        try
        { 
            $role = RoleSpatie::findById($role,'web');
            $permission = Permissions::findOrFail($menu);
            if($estado == 0)
            {
                if($role->hasPermissionTo($permission->name))
                {
                    return response(['data'=> 'El permiso ya se encuentra Asignado','code' => 200]);
                }
                
                $role->givePermissionTo($permission->name);
                return response(['data'=> 'Permiso Asignado','code' => 200]);
            }
            else if($estado == 1 && $role->hasPermissionTo($permission->name))
            {
                $role->revokePermissionTo($permission->name);
                return response(['data'=> 'Permiso Revocado','code' => 200]);
            }
        } 
        catch (\Exception $e) 
        {
            return response(['data'=> 'Error en operacion','code' => 500]);   
        }
    }

   
}
