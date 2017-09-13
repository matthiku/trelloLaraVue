<?php


namespace App\Http\Controllers;

use Auth;
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
      // get the boards with lists eager-loaded
      $boards = Auth::user()->boards->load('lists.cards');

      return response()->json(['message'=>'success', 'data' => $boards], 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required',
      ]);

      $board = new Board( ['name' => $request->get('name')] );
      Auth::user()->boards()->save($board);

      return response()->json(['message'=>'success', 'data' => $board], 200);
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
        $board = Board::with('lists')->find($id);

        if (! $board) // this board wasn't even found
            return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

        // does the user own this board?
        if ($board->user_id == Auth::id())
            return response()->json(['message'=>'success', 'data' => $board], 200);

        // the user doesn't own this board
        return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);
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
      $this->validate($request, [
        'name' => 'required',
      ]);

      // check if board with this id exists
      $board = Board::find($id);
      if (!$board)
          return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

      // does the user own this board?
      if ($board->user_id == Auth::id()) {
          $board->update(['name' => $request->name]);
          return response()->json(['message'=>'success', 'data' => $board], 200);
      }

      // the user doesn't even own this board
      return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // check if board with this id exists
      $board = Board::find($id);
      if (!$board)
          return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

      // does the user own this board?
      if ($board->user_id == Auth::id()) {
          $board->delete();
          // now return the (updated) full list of boards 
          return $this->index();
      }

      // the user doesn't even own this board
      return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);
    }
}
