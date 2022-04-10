@extends('app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <ul class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action" href="{{ url('/') }}">Dashboard</a>
                <a class="list-group-item list-group-item-action" href="{{ route('questions') }}">Questions</a>
                <a class="list-group-item list-group-item-action active" href="{{ route('answers') }}">Answers</a>
                <a class="list-group-item list-group-item-action" href="#">Payments</a>
                <a class="list-group-item list-group-item-action" href="#">Logout</a>
            </ul>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header pt-1 pb-1">
                    <h6>Answers </h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-inverse table-responsive">
                        <thead class="thead-inverse">
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answers</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td scope="row">
                                        {!! Str::limit($item->question, 50) !!}
                                    </td>
                                    <td scope="row">
                                        @if ($item->answer)
                                            {{ $item->answer->answer }}
                                        @endif

                                    </td>
                                    <td>
                                        <span>
                                            <a href="#" data-toggle="modal" data-target="#add{{ $item->id }}"
                                                class="btn btn-primary btn-sm p-1"><small>Add</small></a>
                                            <a href="#" data-toggle="modal" data-target="#assign{{ $item->id }}"
                                                class="btn btn-info btn-sm p-1"><small>Assign</small></a>
                                        </span>
                                    </td>
                                    {{-- add answers modal --}}
                                    <div class="modal fade" id="add{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Answers to Question</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- already added anser popups here --}}
                                                    <p>Already Added Answers</p>
                                                    @if ($item->answers->count() >= 1)
                                                        <ol>
                                                            @foreach ($item->answers as $answer)
                                                                <li style="list-style-type: upper-alpha">
                                                                    {{ $answer->answer }}
                                                                    <a class="badge badge-danger float-right"
                                                                        href="{{ route('delete', ['answers', $answer->id]) }}">x</a>
                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    @else
                                                        <p>No Answer added yet</p>
                                                    @endif
                                                    <br>
                                                    <hr>
                                                    <br>
                                                    <form action="{{ route('store.answer') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="question" value="{{ $item->id }}">
                                                        <div class="form-group">
                                                            <label for="answer">Answer</label>
                                                            <input id="answer" type="text"
                                                                class="form-control @error('answer') is-invalid @enderror"
                                                                required name="answer" value="{{ old('answer') }}" />
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- assign answer to question modal --}}
                                    <div class="modal fade" id="assign{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Answer to Question</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- already added anser popups here --}}
                                                    <p>Assign Answer to Question</p>

                                                    <form action="{{ route('update.assign-answer-question') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="question" value="{{ $item->id }}">
                                                        @if ($item->answers->count() >= 1)
                                                            @foreach ($item->answers as $answer)
                                                                <div class="form-check">
                                                                    <input id="{{ $answer->id }}" @if($item->answer_id == $answer->id) checked @endif class="form-check-input" type="radio" name="answer" value="{{ $answer->id }}">
                                                                    <label for="{{ $answer->id }}" class="form-check-label">{{ $answer->answer }}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <br>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $questions->links() }}
                </div>
                {{-- modal for ading question --}}

            </div>
        </div>
    </div>
@endsection
