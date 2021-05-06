<div class="modal fade modal-bg" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-3">
            <button type="button" class="close text-right d-block" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="material-icons">close</span>
            </button>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <h1 class="text-yellow font-weight-bold mb-lg-4 mb-2">@lang('auth.modal.welcome')</h1>
                            <p class="text-white text-merriweather mb-lg-4 mb-2">
                                @lang('auth.modal.reinsurance1')<br>
                            </p>
                            <p class="text-white text-merriweather mb-lg-5 mb-2">
                                @lang('auth.modal.reinsurance2')<br>
                            </p>
                            <p class="text-white text-merriweather mb-lg-2 mb-0">
                                @lang('auth.modal.find-us')
                            </p>
                            <div class="row rs-line mb-2">
                                <div class="col-12">
                                    <a href="https://www.facebook.com/tripleperformance" target="_blank" class="text-decoration-none">
                                        <img src="images/facebook-white.png" alt="Facebook" class="d-inline-block mr-1">
                                    </a>
                                    <a href="https://twitter.com/TriplePerforma1" target="_blank" class="text-decoration-none">
                                        <img src="images/twitter-white.png" alt="Twitter" class="d-inline-block mr-1">
                                    </a>
                                    <a href="https://medium.com/neayi-en-fran%C3%A7ais" target="_blank" class="text-decoration-none">
                                        <img src="images/medium-white.png" alt="Medium" class="d-inline-block">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 offset-lg-2 bg-white-mobile">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h4 class="text-dark-green font-weight-bold">@lang('auth.modal.create-account')</h4>
                                </div>
                            </div>
                            <div class="row mt-2 mb-4">
                                <div class="col-md-12 login-with-rs pr-0">
                                    <h5 class="text-dark-purple font-weight-bold d-inline-block mr-1">@lang('auth.modal.create-account-with-social-network')</h5>
                                    <img src="images/facebook-logo.png" alt="S'inscrire avec Facebook" class="d-inline-block mr-3 ml-2">
                                    <img src="images/twitter-logo.png" alt="S'inscrire avec Twitter" class="d-inline-block mr-3 ml-2">
                                    <img src="images/google-logo.png" alt="S'inscrire avec Google" class="d-inline-block mr-3 ml-2">
                                </div>
                            </div>
                            <div class="row mb-3 mt-2">
                                <div class="col-md-12">
                                    <h5 class="text-dark-purple font-weight-bold">@lang('auth.modal.create-account-with-your-email')</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="email">@lang('common.email')</label>
                                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Votre adresse email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>@lang('common.password')</label>
                                        <div id="show_hide_password">
                                            <input class="form-control" type="password" placeholder="8 caractères minimum">
                                            <div class="form-icon">
                                                <a href=""><span class="material-icons" aria-hidden="true">visibility</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-right mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-link text-dark-green mr-4" data-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-dark-green text-white px-5 py-2">Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
