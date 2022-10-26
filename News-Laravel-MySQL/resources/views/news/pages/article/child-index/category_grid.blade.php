<div class="posts" style="padding-top: 220px">
    <div class="col-lg-11">
        <div class="row">
            @foreach ($item['related_articles'] as $article)

            <div class="col-lg-12">
                <div class="post_item post_v_small d-flex flex-column align-items-start justify-content-start">
                    @include('news.partials.article.image', ['item' => $article])
                    @include('news.partials.article.content', ['item' => $article, 'lengthContent' => 200,
                    'showCategory' => false])
                </div>
            </div>

            @endforeach

        </div>
        <div class="row">
            <div class="home_button mx-auto text-center"><a href="the-loai/giao-duc-2.html">Xem
                    thêm</a></div>
        </div>
    </div>
</div>
