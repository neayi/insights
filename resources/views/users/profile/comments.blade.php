<div class="tab-pane fade show active following" id="comments" role="tabpanel" aria-labelledby="following-tab">
    <div class="row mt-4">
        <!--div class="col-md-12">
            <p class="empty d-none">Accédez rapidement à toutes les pages que souhaitez mettre de côté dans cette
                section.
            </p>
            <a class="btn btn-outline-gray">
                 <span class="material-icons">
                     sort
                 </span>
                 Dernières pages suivies
            </a>
            <a class="btn btn-outline-gray ml-2 py-2">
                 <span class="material-icons">
                     search
                 </span>
            </a>
            <div class="edit d-inline-block ml-2 py-2" data-toggle="modal" data-target="#followingEdit">
                <a class="btn btn-outline-gray edit-btn mr-2">
                    <span class="material-icons text-dark-green">
                         edit
                    </span>
                </a>
                <a class="text-dark-green edit-link text-decoration-none">
                    Editer
                </a>
            </div>
        </div-->
    </div>
    <div class="row mt-4">
        @foreach($comments as $comment)
            <div class="col-lg-6 followed-page mb-3">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <img src="{{ $picture }}" class="followed-image mb-lg-0 mb-3">
                </div>
                <div class="col-lg-7">
                    <h4>
                        {{ $comment['username'] }}
                        {!! $comment['html'] !!}
                    </h4>
                    <div class="applause-count d-inline-block">
                        {{ $comment['numupvotes'] }} <img src="{{ asset('images/applause.png') }}">
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

