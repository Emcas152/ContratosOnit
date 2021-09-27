<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permissions;
use App\Http\Resources\MenuAccionesResource;
use App\Http\Resources\MenuAccionesStringResource;
use App\Http\Requests\MenuAccionesIdFormRequest;
use App\Models\ViewMenu;
use App\Http\Resources\MenuOptionsResource;
use App\Http\Resources\MenuSubOptionsResource;
use DB;

class MenuAccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $menu = ViewMenu::select('name','url','icon','id','role_id','ext')
        ->where([['nivel_json','=','0'],['role_id','=',$request->get('role_id')]])
        ->get();

        return MenuOptionsResource::collection($menu);
    }

    public function MenuSubPermissions(Request $request)
    {
        $submenu = ViewMenu::where([['nivel_json','=','1'],['role_id','=',$request->get('role_id')]])->get();

        return MenuSubOptionsResource::collection($submenu);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //faltan las validaciones por campo
        try 
        {
            DB::beginTransaction();
            $menuSubAction = Permissions::findOrFail($id);
            $menuSubAction->update($request->all());
            DB::commit();
            return response(['data'=> $menuSubAction,'code' => 200]);

        } catch (\Exception $e) 
        {
            DB::rollBack();
            return response(['data'=> 'Error al actualizar datos','code' => 500]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function showMenu()
    {
    
        $menuSub = Permissions::select('id','url','name','ext','icon','estado','orden','opcion')
        ->orderBy('nivel_json')
        ->where('nivel_json','0')
        ->orwhere('nivel_json','1')
        ->get();

        return response(['data'=> MenuAccionesResource::collection($menuSub),'code' => 200]);

    }


    public function showMenuAction(MenuAccionesIdFormRequest $request)
    {
        $menuSubAction = Permissions::select('id','url','name','ext','icon','estado','orden','opcion')
        ->where('nivel_json','=',0)
        ->get();

        if (!count($menuSubAction)) 
        {
           return response(['data' => '','code'=>204]); 
        }
        return response(['data'=> MenuAccionesStringResource::collection($menuSubAction),'code' => 200]);
    }


}
