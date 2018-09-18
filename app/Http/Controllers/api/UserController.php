<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\User\UserInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $user;
    public function __construct(UserInterface $user){
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->getAll();
        if($users){
            return response()
            ->json(['success' => 1, 'data' => $users]);
        }else{
            return response()
            ->json(['success' => 0, 'data' =>[] ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $validation = Validator::make($request->all(),[ 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);

        if($validation->fails()){

            return response()
            ->json(['success' => 1, 'validation' => $validation->errors() ]);

        } else{

            $array =  [
                'email' => $request->email,
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'postcode' => $request->postcode,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ];

            $users = $this->user->create($array);
            return response()
            ->json(['success' => 1, 'data' => $users]);
        }
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
        $validation = Validator::make($request->all(),[ 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            'email' => [
                'required',
                'email',
                'string',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);

        if($validation->fails()){

            return response()
            ->json(['success' => 1, 'validation' => $validation->errors() ]);

        } else{

            $array =  [
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'postcode' => $request->postcode,
                'phone_number' => $request->phone_number
            ];

            $users = $this->user->update($id, $array);
            return response()
            ->json(['success' => 1, 'data' => $users]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->user->delete($id);
    }

    public function listByPage($page){

        return $this->user->listByPage($page);

    }
}
