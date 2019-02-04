<?php

namespace App\Http\Controllers;

use App\Students;
use Illuminate\Http\Request;
use Response;
class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data'=>Students::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Code...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->input('firstname') || !$request->input('lastname') || !$request->input('email') || !$request->input('address')) {
            return response()->json(['errors'=>array(['code'=>422, 'message'=>'Faltan datos necesarios para el proceso.'])], 422);
        }
        $student = Students::create($request->all());

        $response = Response::make(json_encode(['data'=>$student ]), 201)->header('Location', 'http://localhost/api/students/'.$student ->id)->header('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $students = Students::find($id);
        if (!$students) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se encuentra un estudiante con ese id.'])], 404);
        }
        return response()->json(['status'=>'ok', 'data'=>$students], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function edit(Students $students)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Students::find($id);
        if (!$student) {
            return response()->json(['errors'=>array(['code'=>404, 'message'=>'No se encuentra un estudiante con ese id.'])], 404);
        }
        $firstname=$request->input('firstname');
        $lastname=$request->input('lastname');
        $email=$request->input('email');
        $address=$request->input('address');

        if ($request->method() === 'PATCH') {
            $bus = false;
            if ($firstname) {
                $student->firstname = $firstname;
                $bus=true;
            }
            if ($lastname) {
                $student->lastname = $lastname;
                $bus=true;
            }
            if ($email) {
                $student->email = $email;
                $bus=true;
            }
            if ($address) {
                $student->address = $address;
                $bus=true;
            }
            if ($bus) {
                $student->save();
                return response()->json(['status'=>'ok','data'=>$student], 200);
            } else {
                return response()->json(['errors'=>array(['code'=>304, 'message'=>'No se ha modificado ningún dato de estudiante.'])], 304);
            }
        }
        if (!$firstname || !$lastname || !$email || !$address) {
            return response()->json(['errors'=>array(['code'=>422, 'message'=>'Faltan valores para completar el procesamiento.'])], 422);
        }
        $student->firstname = $firstname;
        $student->lastname = $lastname;
        $student->email = $email;
        $student->address = $address;
        $student->save();
        return response()->json(['status'=>'ok','data'=>$student], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Students::find($id);
        if (!$student) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se encuentra un estudiante con ese id.'])], 404);
        }
        $student->delete();
        return response()->json(['status'=>'ok', 'data'=>$student], 200);
    }
}
