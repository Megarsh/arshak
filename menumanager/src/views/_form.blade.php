<div class="dd" id='#nestable'>
    <ol class="dd-list">
        {{ csrf_field() }}
        {!! $menustr !!}
    </ol>
</div>

@foreach($menu as $key => $value)
    <div id='responsive{{ $value["id"] }}' class='modal fade' tabindex='-1' data-width='760'>
        <form class='form-horizontal form-row-seperated portlet-form' method="post" @if(@$value['id'] != 'create') action="/content/menu/{{ $value['id'] }}" @else action="/content/menu" @endif>
            {{ csrf_field() }}
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
                @if(@$value['id'] == 'create')
                <h3>Create new menu item</h3>
                @else
                <input type="hidden" name="_method" value="PUT" placeholder="">
                <input type="hidden" name="_id" value="{{ @$value->id }}" placeholder="">
                <h3>Edit menu item #{{ @$value->id }}</h3>
                @endif
            </div>
            <div class='modal-body'>
                <div class='form-body'>
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>Language:</label>
                        <div class='col-md-10'>
                            <select class='table-group-action-input form-control input-medium inline' name='lang'>
                                @foreach( Config::get('cmsfly.app.content.languages') as $languageCodes => $language )
                                <option value='{{$languageCodes}}' @if((@$value->lang == $languageCodes) or (Session::get('contentlang') == $languageCodes)) selected='selected' @endif>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>Title:</label>
                        <div class='col-md-10'><input name='title' type='text' class='form-control' value="{{ @$value->title }}"></div>
                    </div>
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>Description:</label>
                        <div class='col-md-10'><input name='descr' type='text' class='form-control' value="{{ @$value->descr }}"></div>
                    </div>
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>Url:</label>
                        <div class='col-md-10'><input name='url' type='text' class='form-control' value="{{ @$value->url }}"></div>
                    </div>
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>OnOff:</label>
                        <div class='col-md-10'>
                            <select class="table-group-action-input form-control input-medium inline" name="onoff">
                                <option value="n" @if(@$value->onoff == 'n') selected="selected" @endif>{{ trans('datatables.draft')}}</option>
                                <option value="y" @if(@$value->onoff == 'y') selected="selected" @endif>{{ trans('datatables.published')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' data-dismiss='modal' class='btn'>Close</button>
                <button type='submit' class='btn btn-primary'>Save changes</button>
            </div>
        </form>
    </div>
@endforeach