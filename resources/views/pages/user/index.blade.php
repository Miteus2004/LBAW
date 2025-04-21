@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users</h1>
    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->user_role === 'banned_user')
                                <span class="badge bg-danger text-white">banned</span>
                            @elseif($user->user_role === 'authenticated_user')
                                <span class="badge">student</span>
                            @else
                                <span class="badge">{{ $user->user_role }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>

                            @can('update', $user)
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            @endcan

                            @can('change_role', $user)
                                <form action="{{ route('users.changeRole', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        @if ($user->user_role === 'authenticated_user')
                                            <i class="fas fa-arrow-up mr-1"></i> Promote
                                        @else
                                            <i class="fas fa-arrow-down mr-1"></i> Demote
                                        @endif
                                    </button>
                                </form>
                            @endcan

                            @can('ban', $user)
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to ban this user?')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-ban mr-1"></i> Ban
                                    </button>
                                </form>
                            @endcan

                            @can('unban', $user)
                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unban this user?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check mr-1"></i> Unban
                                    </button>
                                </form>
                            @endcan

                            @can('delete', $user)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            <ul class="pagination pagination-sm">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">Previous</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($users->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $users->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">Next</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next</span>
                    </li>
                @endif
            </ul>
        </div>
    @endif
    @can('create', App\Models\User::class)
        <div class="mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus mr-1"></i> Create New User
            </a>
        </div>
    @endcan
</div>
@endsection