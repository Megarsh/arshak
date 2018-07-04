<?php

namespace Arshak\Menumanager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Menu;
use App\Functions;
use Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = Menu::where('lang', '=', Session::get('contentlang'))->orderBy('weight', 'desc')->get();
        $menustr = $this->make_menu($this->make_rec($menu));
        $menu[] = ['id' => 'create'];
        return view('menumanager::index')->with('menustr', $menustr)->with('menu', $menu);
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
        Menu::create($request->all());
       
        flash(trans('main.created-success'));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu=Menu::findorFail($id);

        $menu->update($request->all());
    
        flash(trans('main.edited-success'));
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::find($id)->delete();
        Menu::where("parent_id" , "=", "$id")->delete();
    }


    /**
     * Save the current menu order
     *
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request, $order = false, $parent_id = 0)
    {
        if (!$order) {
            $orderr = $request->all();
            $order = json_decode($orderr['order'], true);
        }

        foreach($order as $key => $value) {
            $ord = 100 - $key;
            Menu::where('id', $value['id'])->update(["parent_id" => "$parent_id", "weight" => "$ord"]);
            if (@$value['children']) {
                $this->saveOrder($request, @$value['children'], $value['id']);
            }
        }
        flash(trans('main.edited-success'));
    }

    /**
     * Change onoff state
     *
     * @return \Illuminate\Http\Response
     */
    public function onoff(Request $request)
    {
        $order = $request->all();
        Menu::where('id', $order['id'])->update(["onoff" => "$order[onoff]"]);
    }


    public function make_rec($array, $pid = 0)
    {
        $menu = array();
        foreach ($array as $value)
        {
            if ($value['parent_id'] == $pid)
            {
                $arr = $value;
                $rec = $this->make_rec($array, $value['id']);
                $menu[$value['id']] = $arr;
                if($rec)
                    $menu[$value['id']]['children'] = $rec;
            }
        }
        return $menu;
    }

    public function make_menu($item)
    {
        global $menustr;
        foreach($item as $key => $value)
        {
            $menustr.= "<li class='dd-item' data-id='$key'>
                <div class='dd-buttons'>
                    <input type='checkbox' class='make-switch onoff_switch' title='$key' data-size='mini'".($value['onoff'] == 'y' ? "checked":"").">&nbsp;
                    <a href='#responsive".$key."' data-toggle='modal' class='btn btn-sm blue'><i class='fa fa-edit'></i>".trans('datatables.edit')."</a>&nbsp;
                    <button class='btn btn-sm btn-outline red btn-delete' data-id='".$key."'><i class='fa fa-times'></i>".trans('datatables.delete')."</button>
                </div>
                <div class='dd-handle'><div class='dd-title'><i class='fa fa-reorder dd-mute'></i>&nbsp;&nbsp;".@$value['title']."<span class='text-muted dd-mute'>&nbsp;&nbsp;&nbsp;&nbsp;".@$value['url']."</span></div></div>";
            if(@$value['children'])
            {
                $menustr.= "<ol class='dd-list'>";
                $menustr = $this->make_menu($value['children']);
                $menustr.= "</ol>";
            }
            $menustr.="</li>";
        }
        return $menustr;
    }

}
