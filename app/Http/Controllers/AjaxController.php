<?php
/**
 * Created by PhpStorm.
 * User: w7uu
 * Date: 29.03.2017
 * Time: 17:46
 */

namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AjaxController extends Controller
{
    public function open()
    {
        if(!isset($_POST['gid'])) return 'cell is not set';
        $cell = $_POST['gid'];

        $id = Session::get('id');
        $hash = Session::get('hash');
        $data = DB::select("SELECT blocks, current FROM games WHERE id=? AND hash=?", [$id, $hash]);

        if (!$data) return 'data is empty';
        $current = json_decode($data[0]->current);
        $blocks = json_decode($data[0]->blocks);

        if($blocks[$cell]->s) return response(["status" => "1", "msg" => "ignore"]);

        // because of zero
        if(!$current){
            $current = [];
        } //

        foreach ($current as $val){
            if($val == $cell){
                return response(["status" => "1", "msg" => "ignore"]);
            }
        }

        $color = $blocks[$cell]->c;
        $current[sizeof($current)] = $cell;

        if (sizeof($current) >= ResponseController::MAX_CURRENT) {
            foreach ($current as $val) {
                if ($blocks[$val]->c != $color) {
                    DB::update("UPDATE games SET current='' WHERE id=?", [$id]);
                    return response(["status" => "1", "msg" => "colors are different", "color" => $color, "blocks" => $current]);
                }
            }

            unset($val);
            foreach ($current as $val) {
                $blocks[$val]->s = 1;
            }

            $blocks = json_encode($blocks);
            DB::update("UPDATE games SET current='', blocks=? WHERE id=?", [$blocks, $id]);
            return response(["status" => "0", "msg" => "ok", "color" => $color]);
        }

        $current = json_encode($current);

        DB::update("UPDATE games SET current=? WHERE id=?", [$current, $id]);
        return response(["status" => "0", "msg" => "ok", "color" => $color]);
    }

    public function debug()
    {
        echo 'id: ' . Session::get('id') . ', hash: ' . Session::get('hash');
    }
}