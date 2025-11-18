<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TaskController extends Controller
{
    public function view(Request $request){
        $status = $request->query('status');
        $task = Task::where('user_id',$request->user()->id)
        ->when($status==='completed',fn($q) => $q->where('completed',true))
        ->when($status === 'pending', fn($q) => $q->where('completed',false))
        ->get();
        return response()->json([
            'task'=>$task,
            'count' => $task->count(),
        ]);
    }
    public function show(Request $request,$id){
        $task = Task::where('id', $id)->where('user_id', $request->user()->id)->first();
        if(!$task){
            return response()->json([
                'message' => 'Task Not Found',
            ], 404);
        }
        return response()->json([
            'task'=>$task,
        ]);

    }
    public function store(Request $request){
        $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'priority'=>'nullable|integer|in:1,2,3',
        'due_date'=>'nullable|date',
        'completed'=>'nullable|boolean'
    ]);
    $task=Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'priority'=> $request->priority?? 2,
        'due_date'=> $request->due_date,
        'completed'=> $request->completed ?? false ,
        'user_id'=>$request->user()->id,
    ]);
        return response()->json([
            'message' => 'seccussfull',
            'task'=> $task
        ],201);
   }
   public function update(Request $request , $id){
        $task = Task::where('id', $id)->where('user_id',$request->user()->id)->first();
        if(!$task){
            return response()->json(['message'=>'Task Not Found'],404);
        }
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'nullable|integer|in:1,2,3',
            'due_date' => 'nullable|date',
            'completed' => 'nullable|boolean'
        ]);
        $task->update([
            'title' => $request->title ?? $task->title,
            'description' => $request->description ?? $task->description,
            'priority' => $request->priority ?? $task->priority,
            'due_date' => $request->due_date ?? $task->due_date,
            'completed' => $request->completed ?? $task->completed,
        ]);
        return response()->json([
            'message' => 'Task Updated  Successfully',
            'task' => $task
        ]);
   }
   public function delete(Request $request,$id){
        $task = Task::where('id', $id)->where('user_id', $request->user()->id)->first();
        if(!$task){
            return response()->json([
                'message'=>'Task Not Found'
            ],404);
        }
        $task->delete();
        return response()->json([
            'message' => 'Task Deleted Successfully'
        ]);
   }
}