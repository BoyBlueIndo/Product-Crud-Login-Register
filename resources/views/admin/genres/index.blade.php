@extends('layout.app')

@section('title', 'Manage Genres')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Genres</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                + Add Genre
            </button>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-dark btn-sm">
                ← Back
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="80">ID</th>
                    <th>Name</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($genres as $genre)
                <tr>
                    <td>{{ $genre->id }}</td>
                    <td class="fw-semibold">{{ $genre->name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $genre->id }}">
                                Edit
                            </button>
                            <form action="{{ route('admin.genres.destroy', $genre->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this genre?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <div class="modal fade" id="editModal{{ $genre->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Genre</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form method="POST" action="{{ route('admin.genres.update', $genre->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <label class="form-label">Genre Name</label>
                                    <input 
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ $genre->name }}"
                                        required
                                    >
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-success">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Genre</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('admin.genres.store') }}">
                @csrf

                <div class="modal-body">
                    <label class="form-label">Genre Name</label>
                    <input 
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="e.g. Action, Adventure, RPG"
                        required
                    >
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Create Genre</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection