<?php
/**
 * Created by PhpStorm.
 * User: w7uu
 * Date: 28.03.2017
 * Time: 20:57
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ResponseController extends Controller
{
    const MAX_CURRENT = 2;
    const MAX_CELLS = 100;
    const MAX_COLORS = 5;
    const HASH_WORD = "bHn24ac9Lk1";

    public function init()
    {
        //$this->dropTables();
        //$this->setTables();

        for ($i = 0; $i < self::MAX_CELLS; $i++) {
            $blocks[$i]['c'] = rand(1, self::MAX_COLORS);
            $blocks[$i]['s'] = 0;
        }

        $json = json_encode($blocks);
        $hash = hash('sha256', self::HASH_WORD . time());

        Session::put('hash', $hash);
        if($this->insertExp([$hash, $json, ''])){
            $data = DB::select("SELECT id FROM games WHERE hash=? AND blocks=?", [$hash, $json]);
            if($data) Session::put('id', $data[0]->id);
        }

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