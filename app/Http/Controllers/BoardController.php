<?php


namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;


class BoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Board::all();
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
        Board::create([
            'name' => $request->name,
            'user_id' => 1,
        ]);

        return response()->json(['message'=>'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $board = Board::findOrFail($id);
        if ($board) 
            return $board;

        return response()->json(['status'=>'error', 'message' => 'invalid request!'], 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Board  $board
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
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        $board = Board::findOrFail($id);
        if ($board) {
            $board->update($request->all());
            return $board;
        }
        return response()->json(['status'=>'error', 'message' => 'invalid request!'], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Board::destroy($id)) 
            return response()->json(['status'=>'success', 'message' => 'destroyed'], 200);
        
        return response()->json(['status'=>'error', 'message' => 'invalid request!'], 403);
    }
}
