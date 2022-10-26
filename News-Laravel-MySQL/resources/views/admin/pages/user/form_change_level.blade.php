@php
use App\Helpers\Form as FormTemplate;
use App\Helpers\Template as Template;
use Illuminate\Support\Facades\Config;

$formLabelAttr = Config::get('zvn.template.form_label_edit');
$formInputAttr = Config::get('zvn.template.form_input');


$inputHiddenID = Form::hidden('id', $item['id'] ?? '');
$inputHiddenTask = Form::hidden('task', 'change-level');



$levelValue = ['default' => '-- Select Level -- ', 'admin' => Config::get('zvn.template.level.admin.name'), 'member' => Config::get('zvn.template.level.member.name')];

$elements = [
    [
    'label' => Form::label('level', 'Level', $formLabelAttr),
    'element' => Form::select('level', $levelValue, $item['level'] ?? '', $formInputAttr)
    ],

    [
    'element' => $inputHiddenID . $inputHiddenTask .Form::submit('Save', ['class' => 'btn btn-success']),
    'type' => "btn-submit-edit"
    ]
];



@endphp


<div class="col-md-6 col-sm-12 col-xs-12">
    <div class="x_panel">
        @include('admin.templates.x_title', ['title' => 'Form Change Password'])
        <div class="x_content">
            {!! Form::open([
            'method' => 'POST',
            'url' => route("$controllerName/change-level-post"),
            'accept-charset' => 'UTF-8',
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal form-label-left',
            'id' => 'main-form'
            ]) !!}

            {!! FormTemplate::show($elements) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>
