@extends('layouts.app')

@section('content')
    <div class="card text-center mt-5">
        <div class="card-header">
            <b>TODO DETAILS</b>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $task->name }}</h5>
            <p class="card-text">{{ $task->description }}.</p>
            <a href="edit/{{ $task->task_id }}"><span class="btn btn-primary">Edit</span></a>
            <a href="delete/{{ $task->task_id }}"><span class="btn btn-danger">Delete</span></a>
        </div>
    </div>
@endsection

