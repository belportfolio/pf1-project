<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ToDo;

class TodoController extends Controller
{
    public function index(){
        $todos = ToDo::all();
        return view('TodoForm', compact('todos'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'tododesc'=>'required'     
        ]);

        $todo = ToDo::create([
            'description' => $request->tododesc,
            'title' => $request->tododesc   
        
        ]);

        if($request->ajax() && $todo) {
            return $todo;
        }

        return back();
    }

    public function destroy(Request $request, $id){

        $todo = ToDo::find($id);

        if($request->ajax() && $todo) {
            if($todo->delete()) {
                return response()->json(['success' =>true]);
            } else {
                return response()->json(['success' =>false]);
            }
        }
        
        return redirect('/todos');
    }

    public function update(Request $request, $id){
        $request->validate([
            'tododesc'=>'required'     
        ]);
            
        $todo = ToDo::find($id);
        $todo->description = $request->tododesc;
        $todo->title = $request->tododesc;
  
        if($request->ajax() && $todo->save()) {
            if($todo->save()) {
                return response()->json(['success' => true , 'todo' => $todo]);
            } else {
                return response()->json(['success' => false]);
            }
        }
        return redirect('/todos');


    }
}