@php
use App\Helpers\Template as Template;
use App\Helpers\URL;
    $name = $item['name'];



    if($showCategory){
        $linkCategory = URL::linkCategory($item['category_id'], $item['category_name']);
    }
    $linkArticle = URL::linkArticle($item['id'], $item['name']);

    $created = Template::showDatetimeFrontend($item['created']);


    if($lengthContent == 'full'){
        $content = $item['content'];
    }else{
        $content = Template::showContent($item['content'], $lengthContent);
    }
@endphp
<div class="post_content">
    @if ($showCategory == true)
        <div class="post_category cat_technology ">
            <a href="{{ $linkCategory }}">{{ $item['category_name'] }}</a>
        </div>
    @endif

        <div class="post_title" style="font-size: 28px"><a href="{{ $linkArticle }}">{{ $name }}</a></div>
        <div class="post_info d-flex flex-row align-items-center justify-content-start">
            <div class="post_author d-flex flex-row align-items-center justify-content-start">
                <div class="post_author_name"><a href="#">Lê Tấn Phúc</a>
                </div>
            </div>
            <div class="post_date"><a href="#">{{ $created }}</a></div>
        </div>

    @if ($lengthContent > 0)
        <div class="post_text">
            <p>{!! $content !!}</p>
        </div>
    @endif

    @if ($lengthContent == 'full')
    <div class="post_text">
        <p>{!! $content !!}</p>
    </div>
@endif

</div>
