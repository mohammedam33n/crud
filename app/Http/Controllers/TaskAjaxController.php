<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\StoretaskRequest;
use App\Http\Requests\UpdatetaskRequest;
use Illuminate\Support\Facades\Response;

class TaskAjaxController extends Controller
{


    public function getTasks(Request $request)
    {
        // http://127.0.0.1:8000/tasks/get/tasks?page=1
        // http://127.0.0.1:8000/tasks/get/tasks?page=1

        $tasks = Task::where('name', 'LIKE', '%' . $request->keyword . '%')->orderBy('id', 'DESC')->paginate(5);
        return  $tasks;


        // $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        // $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        // $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        // $tasks = $tasks->orderBy($sort_by, $order_by);
        // $tasks = $tasks->paginate($limit_by);
    }


    public function getUsers()
    {
        return User::all('id', 'name');
    }


    /*
    function search()
    {

        $this->getTasks();

        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $categoryId = (isset(\request()->category_id) && \request()->category_id != '') ? \request()->category_id : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';

        $pages = Page::wherePostType('page');
        if ($keyword != null) {
            $pages = $pages->search($keyword);
        }
        if ($categoryId != null) {
            $pages = $pages->whereCategoryId($categoryId);
        }
        if ($status != null) {
            $pages = $pages->whereStatus($status);
        }

        $pages = $pages->orderBy($sort_by, $order_by);
        $pages = $pages->paginate($limit_by);

        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');
        return view('backend.pages.pages.index', compact('categories', 'pages'));
    }
    */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('taskajax.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretaskRequest $request)
    {
        Task::create($request->validated());
        return response(['message' => 'Task added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return Response::json($task);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response(['message' => 'Task updateed successfully']);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response(['message' => 'Task deleted successfully']);
        // toastr()->success('Have fun storming the castle!', 'Miracle Max Says');


    }
}
