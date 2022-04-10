@extends('app')

@section('content')
            <div class="row">
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action" href="#">Dashboard</a>
                        <a class="list-group-item list-group-item-action" href="{{ route('questions') }}">Questions</a>
                        <a class="list-group-item list-group-item-action" href="#">Answers</a>
                        <a class="list-group-item list-group-item-action" href="#">Payments</a>
                        <a class="list-group-item list-group-item-action active" href="#">Logout</a>
                    </ul>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pt-0 pb-0">
                            <h6>Home</h6>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
@endsection
