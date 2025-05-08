<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class UserprofileController extends Controller
{
    public function gantifotoprofile(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:12288',
        ]);
    
        $image = $request->file('image');
        $namafile = time() . Auth()->User()->username . '.' . $image->getClientOriginalExtension();
    
        // Save path in public/storage/images
        $destinationPath = public_path('storage/images'); // Store images in public/storage/images/
    
        // Ensure the folder exists or create it if necessary
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true); // Create the folder if it doesn't exist
        }
    
        // Process the image
        $imgFile = Image::make($image->getRealPath());
        $imgFile->orientate();
        $imgFile->resize(600, 700, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save($destinationPath . '/' .  $namafile);
    
        // Update user photo in database with the relative path
        $user = Users::findOrFail(Auth()->User()->id);
        $input['foto'] = 'images/' . $namafile; // Use relative path for the image
        $user->update($input);
    
        return back()->with('success', 'Image Uploaded successfully.');
    }
    
    
    public function updateprofile(Request $request, $id)
    {
        $user = Users::findOrFail($id);
        $input = $request->all();
        $input['tanggal_lahir'] = tanggaltodate($request->tanggal_lahir);
        $validator = Validator::make($request->all(), [
            'nik'      => 'required|numeric',
            'tempat_lahir'   => 'required',
            'tanggal_lahir'   => 'required',
            'jenis_kelamin'   => 'required',
        ]);

        if ($validator->fails()) {
            $error =  $validator->errors();
            return Redirect::back()->withErrors(['msg' => explode(',', $error)]);
        }
        $user->update($input);
        return Redirect::back()->withErrors(['msg' => 'berhasil update']);
    }
    public function index()
    {
        $profile = Users::find(Auth()->User()->id);
        $data = [
            'profile' => $profile
        ];
        return view('users.profile', $data);
    }
    public function ttduser()
    {
        $user = Users::find(Auth()->User()->id);
        $img = public_path('userfoto/ttd/') . $user->id . '.png';
        return view('users.ttduser', compact([
            'user'
        ]));
    }
    public function simpanttduser(Request $request)
    {
        $folderPath = public_path('userfoto/signature/');

        $image_parts = explode(";base64,", $request->signed);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1]; //png . svg.dll

        $image = base64_decode($image_parts[1]);

        $namafile = Auth()->User()->id . '.' . $image_type;
        //$imagePath = public_path('/images');
        $imgFile = Image::make($image);
        $imgFile->orientate();
        $imgFile
            ->resize(460, 650, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('userfoto/ttd') . '/' .  $namafile);

        return back()->with('success', 'Image Uploaded successfully.');
    }
    public function editpass()
    {
        return view('users.editpass');
    }
    public function simpaneditpass(Request $request)
    {
        $id = Auth::id();
        $user = Users::findOrFail($id);

        $rules = [
            'oldpassword'       => 'required',
            'npassword'         => 'required|same:ncpassword|different:oldpassword',
            'ncpassword'        => 'required|same:npassword'
        ];
        $messages = array(
            'same'    => 'Form Password baru dan Konfirm password baru harus sama.',
            'required'    => 'Form  harus di isi.',
            'different'    => 'Tidak boleh sama dengan password lama.',
        );
        $this->validate($request, $rules, $messages);
        if (Hash::check($request->oldpassword, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->npassword)
            ])->save();

            $request->session()->flash('success', 'Password berhasil dirubah');
            return redirect()->route('epass');
        } else {
            $request->session()->flash('error', 'Password lama tidak sama');
            return redirect()->route('epass');
        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
