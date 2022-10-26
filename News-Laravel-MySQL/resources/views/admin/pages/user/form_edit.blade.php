@php
use App\Helpers\Form as FormTemplate;
use App\Helpers\Template as Template;
use Illuminate\Support\Facades\Config;

$formLabelAttr = Config::get('zvn.template.form_label');
$formInputAttr = Config::get('zvn.template.form_input');

$statusValue = ['default' => '-- Select Status -- ', 'active' => Config::get('zvn.template.status.active.name'),
'inactive' => Config::get('zvn.template.status.inactive.name')];

$levelValue = ['default' => '-- Select Level -- ', 'admin' => Config::get('zvn.template.level.admin.name'), 'member' =>
Config::get('zvn.template.level.member.name')];

$inputHiddenID = Form::hidden('id', $item['id'] ?? '');
$inputHiddenAvatar = Form::hidden('avatar_current', $item['avatar'] ?? '');
$inputHiddenTask = Form::hidden('task', 'add');


$elements = [
[
'label' => Form::label('username', 'Username', $formLabelAttr),
'element' => Form::text('username', $item['username'] ?? '' , $formInputAttr)
],

[
'label' => Form::label('email', 'Email', $formLabelAttr),
'element' => Form::text('email', $item['email'] ?? '', $formInputAttr)
],

[
'label' => Form::label('fullname', 'Fullname', $formLabelAttr),
'element' => Form::text('fullname', $item['fullname'] ?? '', $formInputAttr)
],


[
'label' => Form::label('status', 'Status', $formLabelAttr),
'element' => Form::select('status', $statusValue, $item['status'] ?? '', $formInputAttr)
],


[
'label' => Form::label('avatar', 'Avatar', $formLabelAttr),
'element' => Form::file('avatar', $formInputAttr),
'avatar' => (!empty($item['id'])) ? Template::showItemImage($controllerName, $item['avatar'], $item['name']) : null,
'type' => 'avatar'
],

[
'element' => $inputHiddenID . $inputHiddenAvatar . $inputHiddenTask . Form::submit('Save', ['class' => 'btn
btn-success']),
'type' => "btn-submit"
]

];



@endphp


<div class="col-md-6 col-sm-12 col-xs-12">
    <div class="x_panel">
        @include('admin.templates.x_title', ['title' => 'Form Edit Info'])
        <div class="x_content">
            {!! Form::open([
            'method' => 'POST',
            'url' => route("$controllerName/save"),
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
