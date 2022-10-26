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
                                <th class="column-title">Name</th>
                                <th class="column-title">Trạng thái</th>
                                <th class="column-title">Hiển thị Home</th>
                                <th class="column-title">Kiểu hiển thị</th>
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

                                        $listActions = Template::showButtonAction($controllerName, $id);

                                        $display    = Template::showItemSelect($id, $value['display'], 'display');
                                        $status     = Template::showItemStatus($controllerName, $id, $value['status']);
                                        $isHome     = Template::showItemIsHome($id, $value['is_home']);
                                        $created    = Template::showItemHistory($value['created_by'], $value['created']);
                                        $modified   = Template::showItemHistory($value['modified_by'], $value['modified']);
                                    @endphp

                                    <tr class="{{ $class }} pointer">
                                        <td>{{ $index }}</td>
                                        <td width="40%">{!! $name !!}</td>
                                        <td>{!! $status !!}</td>
                                        <td>{!! $isHome !!}</td>
                                        <td>{!! $display !!}</td>
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
