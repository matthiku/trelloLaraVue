<?php


namespace App\Http\Controllers;

use Auth;
use App\Lists;
use App\Board;
use Illuminate\Http\Request;


class ListController extends Controller
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


  /**
   * Validate the board ID
   *
   */
  protected function checkBoard($board_id)
  {
    // first get the board model
    $board = Board::find($board_id);
    if (! $board) // this board wasn't even found
      return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

    // does the user even own this board?
    if (! $board->user_id == Auth::id())
      return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);

    return $board;
  }




  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($board_id)
  {
    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    return response()->json(['lists' => $board->lists]);
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $board_id)
  {
    $this->validate($request, [
      'name' => 'required',
    ]);

    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    $lists = new Lists( ['name' => $request->get('name')] );
    $board->lists()->save($lists);

    return response()->json(['message'=>'success', 'data' => $lists], 200);
  }




  /**
   * Display the specified resource.
   *
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function show($board_id, $lists_id)
  {
    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    // get the list model
    $lists = $board->lists()->find($lists_id);

    if (! $lists) // this list wasn't even found
      return response()->json(['status'=>'error', 'message' => 'wrong ID number or invalid request!'], 403);

    return response()->json(['message'=>'success', 'data' => $lists], 200);
  }




  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required',
    ]);

    // check if list with this id exists
    $lists = Lists::find($id);
    if (!$lists)
      return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

    // does the user own this list?
    if ($lists->board->user_id == Auth::id()) {
      $lists->update(['name' => $request->name]);
      return response()->json(['message'=>'success', 'data' => $lists], 200);
    }

    // the user doesn't even own this list
    return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    // check if list with this id exists
    $lists = Lists::find($id);
    if (!$lists)
      return response()->json(['status'=>'error', 'message' => 'ID number wrong or invalid request!'], 403);

    // does the user own this list?
    if ($lists->board->user_id == Auth::id()) {
      $lists->delete();
      return response()->json(['message'=>'success', 'data' => $lists], 200);
    }

    // the user doesn't even own this list
    return response()->json(['status'=>'error', 'message' => 'unauthorized!'], 401);
  }
}
