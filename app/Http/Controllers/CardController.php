<?php


namespace App\Http\Controllers;

use Auth;
use App\Board;
use App\Lists;
use App\Card;
use Illuminate\Http\Request;


class CardController extends Controller
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
      return response()->json(['status'=>'error', 'message' => 'wrong ID number or invalid request!'], 403);

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
  public function index($board_id, $list_id)
  {
    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    $list = $board->lists()->find($list_id);

    return response()->json(['cards' => $list->cards]);
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $board_id, $list_id)
  {
    $this->validate($request, [
      'name' => 'required',
    ]);

    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    $list = $board->lists()->find($list_id);

    $card = new Card([
      'name' => $request->get('name'),
      'description' => $request->get('description'),
    ]);
    $card = $list->cards()->save($card);

    return response()->json(['message'=>'success', 'data' => $card], 200);
  }




  /**
   * Display the specified resource.
   *
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function show($board_id, $list_id, $card_id)
  {
    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    // get the specified list
    $list = $board->lists()->find($list_id);
    if (! $list) // but this list wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong list ID number or invalid request!'], 403);

    // get the specified card
    $card = $list->cards()->find($card_id);
    if (! $card) // but this card wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong card ID number or invalid request!'], 403);

    return response()->json(['message'=>'success', 'data' => $card], 200);
  }




  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $board_id, $list_id, $card_id)
  {
    $this->validate($request, [
      'name' => 'required',
    ]);

    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    // get the specified list
    $list = $board->lists()->find($list_id);
    if (! $list) // but this list wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong list ID number or invalid request!'], 403);

    // get the specified card
    $card = $list->cards()->find($card_id);
    if (! $card) // but this card wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong card ID number or invalid request!'], 403);

    $card->update([
      'name' => $request->name,
      'description' => $request->get('description'),
    ]);

    return response()->json(['message'=>'success', 'data' => $card], 200);
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Lists  $lists
   * @return \Illuminate\Http\Response
   */
  public function destroy($board_id, $list_id, $card_id)
  {
    // first check the board
    $board = $this->checkBoard($board_id);
    if (get_class($board) == 'Illuminate\Http\JsonResponse')
      return $board;

    // get the specified list
    $list = $board->lists()->find($list_id);
    if (! $list) // but this list wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong list ID number or invalid request!'], 403);

    // get the specified card
    $card = $list->cards()->find($card_id);
    if (! $card) // but this card wasn't found
      return response()->json(['status'=>'error', 'message' => 'wrong card ID number or invalid request!'], 403);

    $card = $card->delete();
    return response()->json(['message'=>'success', 'data' => $card], 200);
  }
}
