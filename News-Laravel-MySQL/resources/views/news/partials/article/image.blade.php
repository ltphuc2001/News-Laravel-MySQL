@php

    $name = $item['name'];
    $image = asset('images/article/' . $item['thumb']);

    $class = "";
    if(!empty($type) && $type == 'single'){
        $class = "img-fluid w-100";
    }
@endphp
<div class="post_image"><img src="{{ $image }}" alt="{{ $name }}" class="{{ $class }}"></div>
