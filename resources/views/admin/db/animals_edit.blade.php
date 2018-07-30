@extends('admin.layout.app')

@section('body-class', 'gallery-page')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Редагування тварини</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
                                <span class="glyphicon glyphicon-tasks"></span>Картка тварини</div>
                        </div>
                        <div class="panel-body pn">

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
                                <span class="glyphicon glyphicon-tasks"></span>Файли тварини</div>
                        </div>
                        <div class="panel-body pn">
                            <div id="mix-container">
                                @foreach($animal->images as $image)
                                    <div class="mix label1 folder1">
                                        <div class="panel p6 pbn">
                                            <div class="of-h">
                                                <img src="/{{ $image->path }}"
                                                     class="img-responsive" title="{{ $image->name }}">
                                                <div class="row table-layout">
                                                    <div class="col-xs-12">
                                                        <h6>{{ $image->name }}.{{ $image->extension }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                    @foreach($animal->documents as $document)
                                        <div class="mix label1 folder1">
                                            <div class="panel p6 pbn">
                                                <div class="of-h">
                                                    <img src="/img/file.png"
                                                         class="img-responsive" title="{{ $document->name }}">
                                                    <div class="row table-layout">
                                                        <div class="col-xs-12">
                                                            <h6>{{ $document->name }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
    <script src="/js/admin/jquery.mixitup.min.js"></script>
    <script src="/js/admin/jquery.magnific-popup.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            var $container = $('#mix-container'); // mixitup container

            // Instantiate MixItUp
            $container.mixItUp({
                controls: {
                    enable: false // we won't be needing these
                },
                animation: {
                    duration: 400,
                    effects: 'fade translateZ(-360px) stagger(45ms)',
                    easing: 'ease'
                },
                callbacks: {
                    onMixFail: function() {}
                }
            });


            // Add Gallery Item to Lightbox
            $('.mix img').magnificPopup({
                type: 'image',
                callbacks: {
                    beforeOpen: function(e) {
                        // we add a class to body to indicate overlay is active
                        // We can use this to alter any elements such as form popups
                        // that need a higher z-index to properly display in overlays
                        $('body').addClass('mfp-bg-open');

                        // Set Magnific Animation
                        this.st.mainClass = 'mfp-zoomIn';

                        // Inform content container there is an animation
                        this.contentContainer.addClass('mfp-with-anim');
                    },
                    afterClose: function(e) {

                        setTimeout(function() {
                            $('body').removeClass('mfp-bg-open');
                            $(window).trigger('resize');
                        }, 1000)

                    },
                    elementParse: function(item) {
                        // Function will fire for each target element
                        // "item.el" is a target DOM element (if present)
                        // "item.src" is a source that you may modify
                        item.src = item.el.attr('src');
                    },
                },
                overflowY: 'scroll',
                removalDelay: 200, //delay removal by X to allow out-animation
                prependTo: $('#content_wrapper')
            });
        });
    </script>
@endsection