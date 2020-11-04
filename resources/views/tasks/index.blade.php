@extends('layouts.app')

@section('content')
<!-- ここにページ毎のコンテンツを書く -->
    <h1>タスク一覧</h1>

    @if (count($tasks) > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>タスク</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->content }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    {{-- タスク登録ページへのリンク --}}
    {!! link_to_route('tasks.create', '新規タスクの登録', [], ['class' => 'btn btn-primary']) !!}

    @foreach ($tasks as $task)
        <tr>
            {{-- タスク詳細ページへのリンク --}}
                <td>{!! link_to_route('tasks.show', $task->id, ['task' => $task->id]) !!}</td>
                <td>{{ $task->content }}</td>
        </tr>
    @endforeach    
    
@endsection
