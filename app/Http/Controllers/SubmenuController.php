<?php

namespace App\Http\Controllers;

use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SubmenuController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $innerjoin = \DB::table('sys_submenu')
            ->join('sys_menu', 'sys_submenu.menu_id', '=', 'sys_menu.id')
            ->get(['sys_submenu.*', 'sys_menu.menu_name']);
        return $innerjoin;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'menu_id' => 'required',
            'submenu_name' => 'required|string|unique:sys_submenu,submenu_name',
            'secondlevel' => 'nullable',
            'path_direction' => 'nullable',
        ]);

        $submenu = Submenu::create($field);
        return ['submenu' => $submenu, 'message' => 'Sub-Menu added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Submenu $submenu)
    {
        $id = $submenu->id;
        $subdepartment = \DB::table('sys_submenu')
            ->join('sys_menu', 'sys_submenu.menu_id', '=', 'sys_menu.id')->where('sys_submenu.id', "=", $id)
            ->get(['sys_submenu.*', 'sys_menu.menu_name']);
        return $subdepartment;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submenu $submenu)
    {
        $field = $request->validate([
            'menu_id' => 'required',
            'submenu_name' => 'required',
            'path_direction' => 'nullable',
        ]);

        $submenu->update($field);
        return [$submenu];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submenu $submenu)
    {
        $submenu->delete();
        return ['message' => 'Sub-Menu was deleted!'];
    }
}
