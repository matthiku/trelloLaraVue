<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $topic = chr(27)."[0;36mSeeding: ".chr(27)."[0m";

    factory(App\User::class, 5)->create()
      ->each( function ($u) {
        echo "- creating new user - $u->username\r";
        # always have the first user with a static email address
        if ($u->id === 1) {
          $u->email = 'testuser@kuhs.com';
          $u->save();
        }
        # for each user, create a random number of boards
        $boards = factory(App\Board::class, rand(1,5))->make();
        # attach the boards to the user
        foreach ($boards as $board) {
          $u->boards()->save($board);
        }
      });
    echo $topic . " 5 users added. \t\t\n";

    # get all boards and attach lists to it
    foreach (App\Board::get() as $key => $board) {
      echo '- adding lists to board - ' . $board->name ."\r";
      # code...
      $lists = factory(App\Lists::class, rand(1,5))->make();
      foreach ($lists as $list) {
        # code...
        $board->lists()->save($list);
      }
    }
    echo $topic . strval($key+1) . " boards with random # of lists added.\t\t\n";

    # get all Lists and attach cards to it
    foreach (App\Lists::get() as $key => $list) {
      echo '- adding cards to lists - ' . $list->name ."\r";
      # code...
      $cards = factory(App\Card::class, rand(1,5))->make();
      foreach ($cards as $card) {
        # code...
        $list->cards()->save($card);
      }
    }
    echo $topic . strval($key+1) . " lists with random # of cards added.\t\t\n";
  }
}
