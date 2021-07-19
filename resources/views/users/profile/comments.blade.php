<div class="tab-pane fade show active following" id="comments" role="tabpanel" aria-labelledby="following-tab">
    <div class="row mt-4">
        @if(!empty($comments))
            @foreach($comments as $comment)
                <div class="col-lg-6 followed-page">
                    <div class="card mb-3">
                        <div class="row no-gutters position-static">
                            @if(isset($comment['picture']))
                            <div class="col-md-4 col-lg-5">
                                <img src="{{ $comment['picture'] }}" class="followed-image card-img">
                            </div>
                            @endif
                            <div class="col-md-8 col-lg-7 position-static">
                                <div class="card-body py-2">
                                    <h4 class="card-title mb-0">
                                        <a class="stretched-link" style="color: inherit; text-decoration: none;" target="_blank" href="{{config('neayi.wiki_url').'/index.php?curid='.$comment['real_page_id']}}#cs-comments">{{ $comment['title'] }}</a>
                                    </h4>
                                    <div class="card-text muted comment-date">{!! $comment['date'] !!}</div>
                                    <div class="card-text muted comment-extract">{!! $comment['html'] !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-light small">
                Pas encore de commentaires ni de questions !
            </div>
        @endif
    </div>
</div>

