<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MenuAccessController extends Controller implements HasMiddleware
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
        MenuAccess::all();
    }

    public function del_menu_access($id)
    {
        \DB::table('emp_menu_access')->where('employee_id', '=', $id)->delete();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'menu' => 'array|required'
        ]);

        $employee_id = $request->employee_id;
        $menu = $request->menu;

        \DB::table('emp_menu_access')->where('employee_id', $employee_id)->delete();

        $createdMenu = [];
        foreach ($menu as $rec) {
            $createdMenu[] = [
                'employee_id' => $employee_id,
                'menu_id' => $rec['menu_id'],
                'submenu_id' => $rec['submenu_id'],
            ];
        }

    if (!empty($createdMenu)) {
        \DB::table('emp_menu_access')->insert($createdMenu);
    }

        return response()->json([
            'message' => 'Menu access added successfully',
            'data' => $createdMenu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuAccess $menuAccess)
    {
        return [$menuAccess];
    }

    public function getempmenu($id)
    {
        $empmenu = \DB::table('emp_menu_access')
        ->join('sys_menu', 'emp_menu_access.menu_id', '=', 'sys_menu.id')
        ->join('sys_submenu', 'emp_menu_access.submenu_id', '=', 'sys_submenu.id')
        ->where('emp_menu_access.employee_id', "=", $id)
        ->get(['emp_menu_access.*', 'sys_menu.menu_name', 'sys_submenu.submenu_name']);
        return response()->json(['empmenu' => $empmenu]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuAccess $menuAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuAccess $menuAccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuAccess $menuAccess)
    {
        //
    }
}
