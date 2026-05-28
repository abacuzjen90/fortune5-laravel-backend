<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MenuController extends Controller implements HasMiddleware
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
        return Menu::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'menu_name' => 'required|string|unique:sys_menu,menu_name',
            'secondlevel' => 'nullable',
        ]);

        $menu = Menu::create($field);
        return ['menu' => $menu, 'message' => 'Menu added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return [$menu];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $field = $request->validate([
            'menu_name' => 'required|string',
            'secondlevel' => 'nullable',
        ]);

        $menu->update($field);

        return [$menu];
    }

    public function menudata()
    {
        $res = \DB::table('sys_menu')
            ->join('sys_submenu', 'sys_menu.id', '=', 'sys_submenu.menu_id')
            ->get(['sys_menu.id as menu_id', 'sys_menu.menu_name', 'sys_submenu.id as submenu_id', 'sys_submenu.submenu_name']);
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return ['message' => 'Menu was deleted!'];
    }
}
