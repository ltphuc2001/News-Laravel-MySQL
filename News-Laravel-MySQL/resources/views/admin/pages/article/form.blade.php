@extends('admin.main')

@php
use App\Helpers\Form as FormTemplate;
use App\Helpers\Template as Template;
use Illuminate\Support\Facades\Config;

    $formLabelAttr = Config::get('zvn.template.form_label');
    $formInputAttr  = Config::get('zvn.template.form_input');
    $formCkeditor  = Config::get('zvn.template.form_ckeditor');
    $statusValue = ['default' => '-- Select Status -- ', 'active' => Config::get('zvn.template.status.active.name'), 'inactive' => Config::get('zvn.template.status.inactive.name')];

    $inputHiddenID      = Form::hidden('id', $item['id'] ?? '');


    $elements = [
        [
        'label' => Form::label('name', 'Name', $formLabelAttr),
        'element' => Form::text('name', $item['name'] ?? '' , $formInputAttr)
        ],

        [
        'label' => Form::label('content', 'Content', $formLabelAttr),
        'element' => Form::textArea('content', $item['content'] ?? '' , $formCkeditor)
        ],

        [
        'label' => Form::label('category_id', 'Category', $formLabelAttr),
        'element' => Form::select('category_id', $itemsCategory ,$item['category_id'] ?? '' , $formCkeditor)
        ],

        [
        'label' => Form::label('status', 'Status', $formLabelAttr),
        'element' => Form::select('status', $statusValue, $item['status'] ?? '', $formInputAttr)
        ],

        [
        'label' => Form::label('thumb', 'Thumb', $formLabelAttr),
        'element' => Form::file('thumb', $formInputAttr),
        'thumb'   => (!empty($item['id'])) ? Template::showItemImage($controllerName, $item['thumb'], $item['name']) : null,
        'type'    => 'thumb'
        ],

        [
        'element' => $inputHiddenID . Form::submit('Save', ['class' => 'btn btn-success']),
        'type'  => "btn-submit"
        ]

    ];



@endphp

@section('content')
@include('admin.templates.page_header', ['pageIndex' => false])
@include('admin.templates.error')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            @include('admin.templates.x_title', ['title' => 'Form'])
            <div class="x_content">
                {!! Form::open([
                'method' => 'POST',
                'url' => route('save'),
                'accept-charset' => 'UTF-8',
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal form-label-left',
                'id' => 'main-form'
                ]) !!}

                {!! FormTemplate::show($elements) !!}

                {!! Form::close() !!}
                {{-- <form method="POST" action="http://proj_news.xyz/admin123/slider/save" accept-charset="UTF-8"
                    enctype="multipart/form-data" class="form-horizontal form-label-left" id="main-form">
                    <input name="_token" type="hidden" value="m4wsEvprE9UQhk4WAexK6Xhg2nGQwWUOPsQAZOQ5">
                    <div class="form-group">
                        <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-6 col-xs-12" name="name" type="text"
                                value="??u ????i h???c ph??" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label col-md-3 col-sm-3 col-xs-12">Description</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-6 col-xs-12" name="description" type="text"
                                value="T???ng h???p c??c tr????ng tr??nh ??u ????i h???c ph?? h??ng tu???n..." id="description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control col-md-6 col-xs-12" id="status" name="status">
                                <option value="default">Select status</option>
                                <option value="active" selected="selected">K??ch ho???t</option>
                                <option value="inactive">Ch??a k??ch ho???t</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="link" class="control-label col-md-3 col-sm-3 col-xs-12">Link</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-6 col-xs-12" name="link" type="text"
                                value="https://zendvn.com/uu-dai-hoc-phi-tai-zendvn/" id="link">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="thumb" class="control-label col-md-3 col-sm-3 col-xs-12">Thumb</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-6 col-xs-12" name="thumb" type="file" id="thumb">
                            <p style="margin-top: 50px;"><img src="http://proj_news.xyz/images/slider/LWi6hINpXz.jpeg"
                                    alt="??u ????i h???c ph??" class="zvn-thumb"></p>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input name="id" type="hidden" value="3">
                            <input name="thumb_current" type="hidden" value="LWi6hINpXz.jpeg">
                            <input class="btn btn-success" type="submit" value="Save">
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
</div>

@endsection
