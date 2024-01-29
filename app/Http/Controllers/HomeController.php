<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = [];  //In case the $home_posts is empty, it will not return NULL, but empty insted
    
        foreach($all_posts as $post)
        {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    public function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users,0,3);
    }

   

    public function showSuggestions()
    {
    // Get the ID of the authenticated user
    $authUserId = Auth::user()->id;
        // $authUserId = Auth::id();

    // Fetch users who are not followed by the authenticated user with pagination
    // $suggested_users = $this->user->where('id', '!=', $authUserId)
    //         ->withTrashed()
    //         ->latest()
    //         ->paginate(8);

    
        $suggested_users = User::where('id', '!=', $authUserId)
                                ->whereDoesntHave('followers', function ($query) use ($authUserId) {
                                    $query->where('follower_id', $authUserId);
                                })
                                ->paginate(8);

    // Return the view with suggested users
    return view('users.suggestion')->with('suggested_users', $suggested_users);
    }

    public function search(Request $request)
{
    
    // Define the number of items per page
    $itemsPerPage = 4;  // You can change this number to whatever you prefer

    // Fetch users matching the search query with pagination
    $users = $this->user->where('name', 'like', '%'.$request->search.'%')
                        ->paginate($itemsPerPage);

    // Return the view with users and search term
    return view('users.search')->with('users', $users)
                               ->with('search', $request->search);

}


    //  public function search(Request $request)
    // {
    //     $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
    //     return view('users.search')->with('users', $users)->with('search', $request->search);
    // }
    
}
