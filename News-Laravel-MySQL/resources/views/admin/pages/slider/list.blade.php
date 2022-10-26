@php
    use App\Helpers\Template  as Template;
    use App\Helpers\Highlight as Highlight;
@endphp

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">#</th>
                                <th class="column-title">Slider Info</th>
                                <th class="column-title">Trạng thái</th>
                                <th class="column-title">Tạo mới</th>
                                <th class="column-title">Chỉnh sửa</th>
                                <th class="column-title">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($items) > 0)
                                @foreach ($items as $key => $value)
                                    @php
                                        $index      = $key + 1;
                                        $id         = $value['id'];
                                        $class      = ($index % 2 == 0) ? 'even' : 'odd';
                                        $name       = Highlight::show($value['name'], $params['search'], 'name');
                                        $desciption = Highlight::show($value['description'], $params['search'], 'desrciption');
                                        $link       = Highlight::show($value['link'], $params['search'], 'link');

                                        $listActions = Template::showButtonAction($controllerName, $id);
                                        $image      = Template::showItemImage($controllerName, $value['thumb'], $value['name']);
                                        $status     = Template::showItemStatus($controllerName, $id, $value['status']);
                                        $created    = Template::showItemHistory($value['created_by'], $value['created']);
                                        $modified   = Template::showItemHistory($value['modified_by'], $value['modified']);
                                    @endphp

                                    <tr class="{{ $class }} pointer">
                                        <td>{{ $index }}</td>
                                        <td width="40%">
                                            <p><strong>Name:</strong> {!! $name !!}</p>
                                            <p><strong>Description:</strong>{!! $desciption !!}</p>
                                            <p><strong>Link:</strong> {!! $link !!}</p>
                                            <p>{!! $image !!}</p>
                                        </td>
                                        <td>{!! $status !!}</td>
                                        <td>{!! $created !!}</td>
                                        <td>{!! $modified !!}</td>
                                        <td class="last">{!! $listActions !!}</td>
                                    </tr>
                                @endforeach

                            @else
                            @include('admin.templates.list_empty', ['colspan' => 6]);
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
