<?php
/**
 * Created by PhpStorm.
 * User: w7uu
 * Date: 28.03.2017
 * Time: 20:57
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ResponseController extends Controller
{
    const MAX_LEVEL = 3;
    const PARAMS_TABLE = [
        [4*4, 2, 5, 30, 60],
        [6*6, 2, 5, 120, 180],
        [10*10, 2, 5, 300, 300]
    ];

    // max, current, colors, wait, play
    // const HASH_WORD = "bHn24ac9Lk1";

    public function index(){
        return view('profile');
    }

    public function bezier(){
        return view('bezier');
    }

    public function init()
    {
        //$this->dropTables();
        //$this->setTables();

        /* for ($i = 0; $i < self::MAX_CELLS; $i++) {
            $blocks[$i]['c'] = rand(1, self::MAX_COLORS);
            $blocks[$i]['s'] = 0;
        }

        $json = json_encode($blocks);
        $hash = hash('sha256', self::HASH_WORD . time());

        Session::put('hash', $hash);
        if($this->insertExp([$hash, $json, ''])){
            $data = DB::select("SELECT id FROM games WHERE hash=? AND blocks=?", [$hash, $json]);
            if($data) Session::put('id', $data[0]->id);
        }*/

        return view('game');
    }

    public function insertExp($exp)
    {
        return DB::statement("INSERT INTO games(hash, blocks, current)
          VALUES(?, ?, ?)", $exp) or false;
    }

    public function setTables()
    {    // sha-2 hashing
        return DB::statement('CREATE TABLE IF NOT EXISTS games(
            id INT(11) NOT NULL AUTO_INCREMENT,
            hash VARCHAR(64) NOT NULL,
            blocks VARCHAR(2048) NOT NULL,
            current VARCHAR(32) NOT NULL,
            PRIMARY KEY (id)
            )') or false;
    }

    public function dropTables()
    {
        return DB::statement('DROP TABLE games') or false;
    }
}