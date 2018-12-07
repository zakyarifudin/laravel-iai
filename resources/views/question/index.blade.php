@extends('layouts.app')

@section('css')
    <link href="{!! asset('assets/vendor/spectrum/spectrum.css') !!}" rel="stylesheet">
	<link href="{!! asset('assets/css/chatter.css') !!}" rel="stylesheet">
	<link href="{!! asset('assets/css/simplemde.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/trumbowyg/ui/trumbowyg.css') !!}" rel="stylesheet">
    <style>
        .trumbowyg-box, .trumbowyg-editor {
            margin: 0px auto;
        }
    </style>
@endsection

@section('content')
    <div id="chatter" class="chatter_home">

        <div id="chatter_hero">
            <div id="chatter_hero_dimmer"></div>
            <!-- jika ada gambar -->
            <!-- <img src="{{ Config::get('chatter.headline_logo') }}"> -->

            <!-- jika tidak ada -->
            <h1>Chatify</h1>
            <p>A simple forum application.</p>
        </div>


        @if(Session::has('chatter_alert'))
    		<div class="chatter-alert alert alert-{{ Session::get('chatter_alert_type') }}">
    			<div class="container">
    	        	<strong><i class="chatter-alert-{{ Session::get('chatter_alert_type') }}"></i> {{ Session::get('chatter_alert_messages') }}</strong>
    	        	{{ Session::get('chatter_alert') }}
    	        	<i class="chatter-close"></i>
    	        </div>
    	    </div>
    	    <div class="chatter-alert-spacer"></div>
    	@endif

        <!-- Alert -->
        <!-- <div class="chatter-alert alert alert-danger">
            <div class="container">
                <p><strong><i class="chatter-alert-danger"></i> {{ Config::get('chatter.alert_messages.danger') }}</strong> Please fix the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div> -->


        <div class="container chatter_container">

            <div class="row">

                <div class="col-md-3 left-column">
                    <!-- SIDEBAR -->
                    <div class="chatter_sidebar">
                        <button class="btn btn-primary" id="new_discussion_btn"><i class="chatter-new"></i> New</button>
                        {{-- <a href="#"><i class="chatter-bubble"></i> All </a>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#"><div class="chatter-box"></div> Lorem ipsum</a></li>
                        </ul> --}}
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <div class="col-md-9 right-column">
                    <div class="panel">
                        <ul class="discussions">
                            @foreach ($results as $key => $post)
                                <li>
                                    <a class="discussion_list" href="{!! route('question.show', ['id' => $post['id']]) !!}">
                                        <div class="chatter_avatar">
                                            <span class="chatter_avatar_circle" style="background-color:#{{ App\Lib\MyHelper::stringToColorCode($post['email']) }}">
                                                {{ $post['char'] }}
                                            </span>
                                        </div>

                                        <div class="chatter_middle">
                                            <h3 class="chatter_middle_title">
                                                {{ $post['title'] }}
                                                {{-- Label Category --}}
                                                {{-- <div class="chatter_cat" style="background-color:#2421">Lorem ipsum</div> --}}
                                            </h3>
                                            <span class="chatter_middle_details">Posted By: <span data-href="#">{{ $post['username'] }}</span> {{ $post['created_at'] }}</span>
                                            <p>{{ $post['description'] }} <strong>Read More</strong></p>
                                        </div>

                                        <div class="chatter_right">

                                            <div class="chatter_count"><i class="chatter-bubble"></i> {{ $post['comments'] }}</div>
                                        </div>

                                        <div class="chatter_clear"></div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div id="pagination">
                        {{ $results->withPath('')->links() }}
                    </div>

                </div>
            </div>
        </div>

        <div id="new_discussion">

            <div class="chatter_loader dark" id="new_discussion_loader">
                <div></div>
            </div>

            <form id="chatter_form_editor" action="{!! route('question.store') !!}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-7">
                        <!-- TITLE -->
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title of" value="" >
                    </div>

                    <div class="col-md-4">
                        <!-- CATEGORY -->
                            {{-- <select id="chatter_category_id" class="form-control" name="chatter_category_id">
                                <option value="">Select a Category</option>
                            </select> --}}
                    </div>

                    <div class="col-md-1">
                        <i class="chatter-close"></i>
                    </div>
                </div><!-- .row -->

                <!-- BODY -->
                <div id="editor">
                    <textarea class="trumbowyg" name="description" placeholder="Type Your Discussion Here...">{{ old('body') }}</textarea>
                </div>

                <div id="new_discussion_footer">
                    {{-- <input type='text' id="color" name="color" /><span class="select_color_text">Select a Color for this Discussion (optional)</span> --}}
                    <button id="submit_discussion" class="btn btn-success pull-right"><i class="chatter-new"></i> Create </button>
                    <a href="#" class="btn btn-default pull-right" id="cancel_discussion">Cancel</a>
                    <div style="clear:both"></div>
                </div>
            </form>

        </div><!-- #new_discussion -->

    </div>

    <input type="hidden" id="current_path" value="{{ Request::path() }}">
@endsection

@section('js')
    <script src="{!! asset('assets/vendor/trumbowyg/trumbowyg.min.js') !!}"></script>
	<script src="{!! asset('assets/vendor/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') !!}"></script>
	<script src="{!! asset('assets/js/trumbowyg.js') !!}"></script>

    <script src="{!! asset('assets/vendor/spectrum/spectrum.js') !!}"></script>
    <script src="{!! asset('assets/js/chatter.js') !!}"></script>

    <script>
        $('document').ready(function(){

            $('.chatter-close').click(function(){
                $('#new_discussion').slideUp();
            });
            $('#new_discussion_btn, #cancel_discussion').click(function(){
                    $('#new_discussion').slideDown();
                    $('#title').focus();

            });

            $("#color").spectrum({
                color: "#333639",
                preferredFormat: "hex",
                containerClassName: 'chatter-color-picker',
                cancelText: '',
                chooseText: 'close',
                move: function(color) {
                    $("#color").val(color.toHexString());
                }
            });

            // if count(error) > 0
                // $('#new_discussion').slideDown();
                // $('#title').focus();



        });
    </script>
@endsection
