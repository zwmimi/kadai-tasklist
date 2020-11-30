<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    }

    public function create()
    {
        //
        $task =new Task;
        //タスク作成ビューを表示
        return view ('tasks.create',[
            'task' =>$task,
            ]);
        
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate ([
            'status' =>'required|max:10',
            'content' =>'required|max:20',
        ]);
        //タスクを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = \Auth::id();
        $task->save();


        // 前のURLへリダイレクトさせる
        return back();

    }


    public function show($id)
    {
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスク詳細ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show',[
                'task' =>$task,
                ]);
        }
        return back();
            }

    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスク編集ビューでそれを表示
        if(\Auth::id() === $task->user_id){
            return view('tasks.edit', [
            'task' => $task,
        ]);
        }
        return back();
    }


    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:20', 
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        if(\Auth::id() === $task->user_id){
        
        $task->status =$request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
