<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Permissions;
use App\Models\Table;
use App\Models\TableName;

class TablesController extends Controller
{
    public function newTable(Request $request){
        Permissions::checkActive();
        $room_id= $request['id'];

       
        Permissions::havePermission("addTable");
        return view('tables.add',compact('room_id'));

    }
  
    public function addnewTable(Request $request)
    {
        Permissions::checkActive();
        Permissions::havePermission("addTable");
        $table = Table::create([
            'room_id' => $request['room_id'],
            'addedByUserId' => auth()->user()->id
            ]);
            if ($table) {
                $id=$table->id;
                for ($i=1; $i <4 ; $i++) { 
                    $room_names = TableName::create([
                        'table_name' => $request['name'],
                        'table_id' => $id,
                        'lang_id' => $i,
                        ]);
                }
              
            }
           
        return back()->withStatus(__('Table successfully added.'));
    }

}
