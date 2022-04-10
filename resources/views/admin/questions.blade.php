@extends('app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <ul class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action" href="{{ url('/') }}">Dashboard</a>
                <a class="list-group-item list-group-item-action active" href="{{ route('questions') }}">Questions</a>
                <a class="list-group-item list-group-item-action" href="{{ route('answers') }}">Answers</a>
                <a class="list-group-item list-group-item-action" href="#">Payments</a>
                <a class="list-group-item list-group-item-action" href="#">Logout</a>
            </ul>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header pt-1 pb-1">
                    <h6>Questions
                        <span class="float-right"><button data-toggle="modal" data-target="#add-question" type="button"
                                class="btn btn-outline-primary btn-sm">Add New</button></span>
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-inverse table-responsive">
                        <thead class="thead-inverse">
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answers</th>
                                <th>Amount</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td scope="row">
                                        {!! $item->question !!}
                                    </td>
                                    <td scope="row">
                                        @foreach ($item->answers as $key => $answers)
                                            <span>
                                                <small>{{ $key + 1 }}-</small>{{ $answers->answer }}
                                                @if ($item->answer_id == $answers->id)
                                                    <span class="badge bagde-primary">&checkmark;</span>

                                                @endif
                                                <br>

                                            </span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td>
                                        <span>
                                            <a href="#" data-toggle="modal" data-target="#edit{{ $item->id }}"
                                                class="btn btn-primary btn-sm p-1"><small>Edit</small></a>
                                            <a href="{{ route('delete', ['questions', $item->id]) }}"
                                                class="btn btn-danger btn-sm p-1"><small>Delete</small></a>
                                        </span>
                                    </td>
                                    {{-- edit modal --}}
                                    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Question</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('update.question', $item->id) }}" method="post">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="question">Question</label>
                                                            <textarea id="question" class="form-control @error('question')  @enderror" name="question">{!! $item->question !!}</textarea>
                                                            @error('question')
                                                                <small class="invalid-feedback">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="amount">Amount</label>
                                                            <input id="amount"
                                                                class="form-control @error('amount')  @enderror" type="number"
                                                                name="amount" value="{{ $item->amount }}" step="5" />
                                                            @error('amount')
                                                                <small class="invalid-feedback">{{ $message }}</small>
                                                            @enderror
                                                        </div>

                                                         <div class="form-group">
                                                            <label for="duration">Duration (<small>In seconds</small>)</label>
                                                            <input id="duration" class="form-control @error('amount') is-invalid @enderror" type="number"
                                                                name="duration" value="{{ $item->duration }}" step="5" />
                                                            @error('duration')
                                                                <small class="invalid-feedback">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
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

                <!-- Modal -->
                <div class="modal fade" id="add-question" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Question</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('store.question') }}" method="post">
                                <div class="modal-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="question">Question</label>
                                        <textarea id="question" class="form-control @error('question') is-invalid  @enderror" name="question">{{ old('question') }}</textarea>
                                        @error('question')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input id="amount" class="form-control @error('amount') is-invalid @enderror" type="number"
                                            name="amount" value="{{ old('amount') }}" step="5" />
                                        @error('amount')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="duration">Duration (<small>In seconds</small>)</label>
                                        <input id="duration" class="form-control @error('amount') is-invalid @enderror" type="number"
                                            name="duration" value="{{ old('duration') }}" step="5" />
                                        @error('duration')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
