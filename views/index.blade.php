@extends('app')

@section('page-title', 'Menu')
@section('page-desc', 'Header Menu')

@section('page-css')
    <link href="/assets/global/plugins/jquery-nestable/jquery.nestable.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- <div class="note note-danger">
                <p> NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only. </p>
            </div> -->
            <!-- Begin: life time stats -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-globe font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Menu</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                            <a href="/content/menu#responsivecreate" class="btn btn-transparent blue btn-outline btn-circle btn-sm " data-toggle="modal">
                                <i class="fa fa-plus"></i>
                                {{ trans('main.add-menu') }}
                            </a>
                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i class="icon-globe"></i>
                                <span class="hidden-xs"> Content - {{Session::get('contentlang')}}</span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                @foreach( Config::get('cmsfly.app.content.languages') as $languageCodes => $language )
                                    <li><a href="/sessionset/contentlang/{{$languageCodes}}">{{ $language }} </a></li>
                                @endforeach
                            </ul>
                       </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="alert alert-warning hide">
                            <strong>Warning!</strong> Live updates enabled. 
                        </div>
                        @include('menumanager::_form')
                        <br>
                        <div><button type='button' id='updateOrder' class='btn btn-primary'>Save changes</button>&nbsp;&nbsp;&nbsp;&nbsp;Live Updates: <input type="checkbox" class="make-switch" id="liveUpdate" data-size="small">&nbsp;&nbsp;&nbsp;&nbsp;<small>Be carefull, if turned on, live updates will instantly affect the site</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->


@endsection


@section('js-plugins')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

@endsection

@section('js-scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('.dd').nestable({
        maxDepth: 3
    });

    var updateOutput = function(e)
    {
        list = e.length ? e : $(e.target),
        output = list.data('output');
        saveMenu(window.JSON.stringify(list.nestable('serialize')));
    };

    updateOutput($('.dd').data('output', $('#nestable-output')));
    $('.dd').nestable().on('change', updateOutput);

    $('#updateOrder').on('click', function(){
        saveMenu(window.JSON.stringify(list.nestable('serialize')), true);
    });
});



function saveMenu(arraied, liveUpdate = false) {
    if ($("#liveUpdate").bootstrapSwitch('state') === true || liveUpdate) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo url()->current(); ?>/order",
            data: { _token: _token, order:arraied }
        });   
    }
}


</script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('click', '.btn-delete', function() {
        $(this).confirmation(
            { 
                singleton: true,
                popout: true,
                placement: 'left',
                title: '{{trans('main.are-you-sure')}}',
                btnOkLabel:'{{trans('main.yes')}}',
                btnCancelLabel:'{{trans('main.no')}}',
                onConfirm: function() {
                    $(this).parent().parent().remove();
                    var itemId = $(this).attr('data-id');
                    $.post('/content/menu/'+itemId, {_method: 'delete', id:$(this).attr('data-id'), _token: $('meta[name="csrf-token"]').attr('content') });      
                },
                
             }
        );
    $(this).confirmation('toggle');
    });
})
</script>
<script type="text/javascript">
$('#liveUpdate').on('switchChange.bootstrapSwitch',function (e,data) {
    if ($("#liveUpdate").bootstrapSwitch('state') === true) {
        $('.alert-warning').removeClass('hide');
    }
    else{
        $('.alert-warning').addClass('hide');
    }
});

$('.onoff_switch').on('switchChange.bootstrapSwitch',function (e,data) {
    if ($(this).bootstrapSwitch('state') === true) {
        var onoff = 'y';
    }
    else{
        var onoff = 'n';
    }
    var _token = $('input[name="_token"]').val();
    $.ajax({
        type: "POST",
        url: "<?php echo url()->current(); ?>/onoff",
        data: { _token: _token, id: $(this).attr('title'), onoff: onoff }
    });   
});

</script>

@stop
