@extends('layouts.app')

@section('title','Suggestion Users')

@section('content')
    
    <div class="row">
        <div class="col-auto mx-auto">
            <h2 class="fw-bold text-black mb-4">Suggested for You</h2>
        </div>
    </div>

    <div class="row">
            @forelse ($suggested_users as $user)
                <div class="col-3">
                    <div class="card p-3 mb-4">
                        <div class="card-body">
                            <div class="col-auto text-center">
                                <a href="{{ route('profile.show',$user->id) }}">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle image-lg2">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-lg2"></i>
                                    @endif
                                </a>
                            </div>
                                
                        
                            <div class="col m-1 text-truncate text-center">
                                <a href="{{ route('profile.show',$user->id) }}" class="mx-auto text-decoration-none text-secondary fw-bold ">{{ $user->name }}</a>
                            </div>
                        </div> 
            
                            <div class="col-auto mx-auto">
                                <form action="{{ route('follow.store',$user->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class ="btn btn-primary text-bold btn-md">Follow</button>
                                </form>
                            </div>
       
                    </div>
                </div>
            
                @empty
                <p class="text-center">No Suggested User Yet</p>

            @endforelse
    
        <div class="d-flex justify-content-center">
            {{ $suggested_users->links() }}
        </div>
    </div>
    
@endsection