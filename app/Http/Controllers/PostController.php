<?php

namespace App\Http\Controllers;

use App\Models\Pets;
use App\Models\Breeds;
use App\Models\TypesOfPets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {

    // Create page
    public function createPostView() {
        return view('pages.createpost', [
            'types' => TypesOfPets::all(),
            'breeds' => Breeds::all()
        ]);
    }

    // Edit page
    public function editPostView(string $pet_id) {
        //dd(Pets::find($pet_id));
        if (Pets::find($pet_id)->users_id != Auth::user()->id)
            return redirect()->back()->with('error', 'You are not the owner of this pet!');
        return view('pages.editpost', [
            'pet' => Pets::find($pet_id),
            'types' => TypesOfPets::all(),
            'breeds' => Breeds::all()
        ]);
    }

    public function createPost(Request $request) {
        $request['kidFriendly'] = $request->has('kidFriendly');
        $request['multipleAnimalsFriendly'] = $request->has('multipleAnimalsFriendly');
        $request['castrated'] = $request->has('castrated');

        // Reminder: Change value in html file to have the text instead of a number //
        if ($request['sex'] == 1)
            $request['sex'] = "Female";
        else if ($request['sex'] == 2)
            $request['sex'] = "Male";
        else
            $request['sex'] = 'Other';
        $pet_credentials = $request->validate([
            'name' => ['required'],
            'description' => ['nullable', 'max:255'],
            'age_in_months' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'sex' => ['required'],
            'location' => ['nullable'],
            'type_id' => ['required'],
            'breeds_id' => ['required'],
            'castrated' => ['nullable'],
            'multipleAnimalsFriendly' => ['nullable'],
            'kidFriendly' => ['nullable'],
            'price' => ['required', 'numeric']
        ]);
        $pet_credentials['users_id'] = Auth::user()->id;
        // Database post create incomming
        $pets = Pets::create($pet_credentials);

        return redirect('/')->with('Post Created');
    }

    public function editPost(Request $request) {
        //dd($request);
        if (Pets::find($request["petId"])->users_id != Auth::user()->id)
            return redirect()->back()->with('error', 'You are not the owner of this pet!');

        $id = $request["petId"];

        $request['kidFriendly'] = $request->has('kidFriendly');
        $request['multipleAnimalsFriendly'] = $request->has('multipleAnimalsFriendly');
        $request['castrated'] = $request->has('castrated');

        // Reminder: Change value in html file to have the text instead of a number //
        if ($request['sex'] == 1)
            $request['sex'] = "Female";
        else if ($request['sex'] == 2)
            $request['sex'] = "Male";
        else
            $request['sex'] = 'Other';
        $pet_credentials = $request->validate([
            'name' => ['required'],
            'description' => ['nullable', 'max:255'],
            'age_in_months' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'sex' => ['required'],
            'location' => ['nullable'],
            'type_id' => ['required'],
            'breeds_id' => ['required'],
            'castrated' => ['nullable'],
            'multipleAnimalsFriendly' => ['nullable'],
            'kidFriendly' => ['nullable'],
            'price' => ['required', 'numeric']
        ]);

        Pets::whereId($request['petId'])->update($pet_credentials);
        return redirect("/pet/{$id}")->with('Post Created');
    }

    public function deletePost(Request $request) {
        //Checks the authenticated users id against the owner id of the pet which was requested for deletion
        if (Pets::find($request["petId"])->users_id != Auth::user()->id)
            return redirect()->back()->with('error', 'You are not the owner of this pet!');

        //Delete pet from database
        Pets::destroy($request["petId"]);
        return redirect('/')->with('message', 'Post succesfully deleted');
    }
}
