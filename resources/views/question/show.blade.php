@extends('layouts.app')

@section('css')
    <link href="{!! asset('assets/vendor/spectrum/spectrum.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/css/chatter.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/css/chatter.css') !!}" rel="stylesheet">
	<link href="{!! asset('assets/css/simplemde.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/trumbowyg/ui/trumbowyg.css') !!}" rel="stylesheet">
    <style>
        .trumbowyg-box, .trumbowyg-editor {
            margin: 0px auto;
        }
    </style>
@stop


@section('content')

<div id="chatter" class="discussion">

	<div id="chatter_header" style="background-color:#263238">
		<div class="container">
			<a class="back_btn" href="{!! route('question') !!}"><i class="chatter-back"></i></a>
			<h1>{{ $data["title"] }}</h1>
            {{-- <span class="chatter_head_details">
                Posted In {{ Config::get('chatter.titles.category') }}
                <a class="chatter_cat" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.category') }}/{{ $discussion->category->slug }}" style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</a>
            </span> --}}
		</div>
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

	{{-- @if (count($errors) > 0)
	    <div class="chatter-alert alert alert-danger">
	    	<div class="container">
	    		<p><strong><i class="chatter-alert-danger"></i> {{ Config::get('chatter.alert_messages.danger') }}</strong> Please fix the following errors:</p>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
	    </div>
	@endif --}}

	<div class="container margin-top">

	    <div class="row">

	        <div class="col-md-12">
				<div class="conversation">
	                <ul class="discussions no-bg" style="display:block;">
                        <li id="question" data-id="{{ $data['id'] }}" data-markdown="0">
                            <span class="chatter_posts">
                                @if(Session::get('id_user') == $data['user_id'])
                                    <div id="delete_warning_{{ $data['id'] }}" class="chatter_warning_delete">
                                        <i class="chatter-warning"></i>Are you sure you want to delete this?
                                        <button class="btn btn-sm btn-danger pull-right delete_response">Yes Delete It</button>
                                        <button class="btn btn-sm btn-default pull-right">No Thanks</button>
                                    </div>
                                    <div class="chatter_post_actions">
                                        @if (@count($data['comments'] ) == 0)
                                            <p class="chatter_delete_btn">
                                                <i class="chatter-delete"></i> Delete
                                            </p>
                                        @endif
                                        <p class="chatter_edit_btn">
                                            <i class="chatter-edit"></i> Edit
                                        </p>
                                    </div>
                                @endif
                                <div class="chatter_avatar">
                                    <span class="chatter_avatar_circle" style="background-color:#{{ App\Lib\MyHelper::stringToColorCode($data['user']['email']) }}">
                                        {{ ucfirst(substr($data['user']['username'], 0, 1)) }}
                                    </span>
                                </div>

                                <div class="chatter_middle">
                                    <span class="chatter_middle_details"><a href="#">{{ $data['user']['username'] }}</a> <span class="ago chatter_middle_details">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($data["created_at"]))->diffForHumans() }}</span></span>
                                    <div class="chatter_body">
                                        <?= $data['description']; ?>
                                    </div>
                                </div>

                                <div class="chatter_clear"></div>
                            </span>
                        </li>
                        @if (isset($data['comments']))
                            @foreach($data['comments'] as $post)
                                <li class="comments" data-id="{{ $post['id'] }}" data-markdown="0">
                                    <span class="chatter_posts">
                                        @if(Session::get('id_user') == $post['user_id'])
                                            <div id="delete_warning_{{ $post['id'] }}" class="chatter_warning_delete">
                                                <i class="chatter-warning"></i>Are you sure you want to delete this?
                                                <button class="btn btn-sm btn-danger pull-right delete_comment_response">Yes Delete It</button>
                                                <button class="btn btn-sm btn-default pull-right">No Thanks</button>
                                            </div>
                                            <div class="chatter_post_actions">
                                                <p class="chatter_delete_comment_btn">
                                                    <i class="chatter-delete"></i> Delete
                                                </p>
                                                <p class="chatter_edit_comment_btn">
                                                    <i class="chatter-edit"></i> Edit
                                                </p>
                                            </div>
                                        @endif
                                        <div class="chatter_avatar">
                                            <span class="chatter_avatar_circle" style="background-color:#{{ App\Lib\MyHelper::stringToColorCode($post['user']['email']) }}">
                                                {{ ucfirst(substr($post['user']['username'], 0, 1)) }}
                                            </span>
                                        </div>

                                        <div class="chatter_middle">
                                            <span class="chatter_middle_details"><a href="#">{{ $post['user']['username'] }}</a> <span class="ago chatter_middle_details">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($post["created_at"]))->diffForHumans() }}</span></span>
                                            <div class="chatter_body">
                                                <?= $post['comment']; ?>
                                            </div>
                                        </div>

                                        <div class="chatter_clear"></div>
                                    </span>
                                </li>
    	                	@endforeach
                        @endif
	                </ul>
	            </div>

	            {{-- <div id="pagination">{{ $posts->links() }}</div> --}}


            	<div id="new_response">

            		<div class="chatter_avatar">
        				<span class="chatter_avatar_circle" style="background-color:#{{ App\Lib\MyHelper::stringToColorCode(Session::get('email')) }}">
        					{{ ucfirst(substr(Session::get('username'), 0, 1)) }}
        				</span>
	        		</div>

		            <div id="new_discussion">


				    	<div class="chatter_loader dark" id="new_discussion_loader">
						    <div></div>
						</div>

			            <form id="chatter_form_editor" action="{!! route('comment.store', ['id' => $data['id']]) !!}" method="POST">
                            {{ csrf_field() }}

					        <!-- BODY -->
					    	<div id="editor">
								<textarea class="trumbowyg" name="comment" placeholder="Type Your Discussion Here...">{{ old('body') }}</textarea>
							</div>

					        <input type="hidden" name="chatter_discussion_id" value="{{ $data["id"] }}">
					    </form>

					</div><!-- #new_discussion -->
                    <div id="discussion_response_email">
						<button id="submit_response" class="btn btn-success pull-right"><i class="chatter-new"></i> Submit Response</button>
					</div>
				</div>

	        </div>


	    </div>
	</div>

</div>

<input type="hidden" id="current_path" value="{{ Request::path() }}">

@stop

@section('js')
	<script>var chatter_editor = 'trumbowyg';</script>
    <script src="{!! asset('assets/vendor/trumbowyg/trumbowyg.min.js') !!}"></script>
    <script src="{!! asset('assets/vendor/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') !!}"></script>
    <script src="{!! asset('assets/js/trumbowyg.js') !!}"></script>

<script>
	$('document').ready(function(){

		var simplemdeEditors = [];

		$('.chatter_edit_btn').click(function(){
			parent = $(this).parents('li');
			parent.addClass('editing');
			id = parent.data('id');
			markdown = parent.data('markdown');
			container = parent.find('.chatter_middle');

			if(markdown){
				body = container.find('.chatter_body_md');
			} else {
				body = container.find('.chatter_body');
				markdown = 0;
			}

			details = container.find('.chatter_middle_details');

			// dynamically create a new text area
			container.prepend('<textarea id="post-edit-' + id + '"></textarea>');
            // Client side XSS fix
            $("#post-edit-"+id).text(body.html());
			container.append('<div class="chatter_update_actions"><button class="btn btn-success pull-right update_chatter_edit"  data-id="' + id + '" data-markdown="' + markdown + '"><i class="chatter-check"></i> Update Response</button><button href="/" class="btn btn-default pull-right cancel_chatter_edit" data-id="' + id + '"  data-markdown="' + markdown + '">Cancel</button></div>');

			// create new editor from text area

            initializeNewTrumbowyg('post-edit-' + id);

		});

        $('.chatter_edit_comment_btn').click(function(){
			parent = $(this).parents('li');
			parent.addClass('editing');
			id = parent.data('id');
			markdown = parent.data('markdown');
			container = parent.find('.chatter_middle');

			if(markdown){
				body = container.find('.chatter_body_md');
			} else {
				body = container.find('.chatter_body');
				markdown = 0;
			}

			details = container.find('.chatter_middle_details');

			// dynamically create a new text area
			container.prepend('<textarea id="post-edit-' + id + '"></textarea>');
            // Client side XSS fix
            $("#post-edit-"+id).text(body.html());
			container.append('<div class="chatter_update_actions"><button class="btn btn-success pull-right update_chatter_edit_comment"  data-id="' + id + '" data-markdown="' + markdown + '"><i class="chatter-check"></i> Update Response</button><button href="/" class="btn btn-default pull-right cancel_chatter_edit_comment" data-id="' + id + '"  data-markdown="' + markdown + '">Cancel</button></div>');

			// create new editor from text area

            initializeNewTrumbowyg('post-edit-' + id);

		});

        $('#question').on('click', '.cancel_chatter_edit', function(e){
			post_id = $(e.target).data('id');
			markdown = $(e.target).data('markdown');
			parent_li = $(e.target).parents('li');
			parent_actions = $(e.target).parent('.chatter_update_actions');
			if(!markdown){
                $(e.target).parents('li').find('.trumbowyg').fadeOut();
			} else {
				$(e.target).parents('li').find('.editor-toolbar').remove();
				$(e.target).parents('li').find('.editor-preview-side').remove();
				$(e.target).parents('li').find('.CodeMirror').remove();
			}

			$('#post-edit-' + post_id).remove();
			parent_actions.remove();

			parent_li.removeClass('editing');
		});

		$('#question').on('click', '.update_chatter_edit', function(e){
			post_id = $(e.target).data('id');
			markdown = $(e.target).data('markdown');

            update_body = $('#post-edit-' + id).trumbowyg('html');

			$.form('update/' + post_id, { _token: '{{ csrf_token() }}', _method: 'PUT', 'description' : update_body }, 'POST').submit();
		});

		$('.comments').on('click', '.cancel_chatter_edit_comment', function(e){
			post_id = $(e.target).data('id');
			markdown = $(e.target).data('markdown');
			parent_li = $(e.target).parents('li');
			parent_actions = $(e.target).parent('.chatter_update_actions');
			if(!markdown){
                $(e.target).parents('li').find('.trumbowyg').fadeOut();
			} else {
				$(e.target).parents('li').find('.editor-toolbar').remove();
				$(e.target).parents('li').find('.editor-preview-side').remove();
				$(e.target).parents('li').find('.CodeMirror').remove();
			}

			$('#post-edit-' + post_id).remove();
			parent_actions.remove();

			parent_li.removeClass('editing');
		});

		$('.comments').on('click', '.update_chatter_edit_comment', function(e){
			post_id = $(e.target).data('id');
			markdown = $(e.target).data('markdown');

            update_body = $('#post-edit-' + id).trumbowyg('html');

			$.form( 'comment/update/' + post_id, { _token: '{{ csrf_token() }}', _method: 'PUT', 'comment' : update_body }, 'POST').submit();
		});

		$('#submit_response').click(function(){
			$('#chatter_form_editor').submit();
		});

		// ******************************
		// DELETE FUNCTIONALITY
		// ******************************

        // Post
		$('.chatter_delete_btn').click(function(){
			parent = $(this).parents('li');
			parent.addClass('delete_warning');
			id = parent.data('id');
			$('#delete_warning_' + id).show();
		});

		$('.chatter_warning_delete .btn-default').click(function(){
			$(this).parent('.chatter_warning_delete').hide();
			$(this).parents('li').removeClass('delete_warning');
		});

		$('.delete_response').click(function(){
			post_id = $(this).parents('li').data('id');
			$.form('delete/' + post_id, { _token: '{{ csrf_token() }}', _method: 'DELETE'}, 'POST').submit();
		});

        // Comment
        $('.chatter_delete_comment_btn').click(function(){
			parent = $(this).parents('li');
			parent.addClass('delete_warning');
			id = parent.data('id');
			$('#delete_warning_' + id).show();
		});

		$('.chatter_warning_delete .btn-default').click(function(){
			$(this).parent('.chatter_warning_delete').hide();
			$(this).parents('li').removeClass('delete_warning');
		});

		$('.delete_comment_response').click(function(){
			post_id = $(this).parents('li').data('id');
			$.form('comment/delete/' + post_id, { _token: '{{ csrf_token() }}', _method: 'DELETE'}, 'POST').submit();
		});

	});
</script>

<script src="{!! asset('assets/js/chatter.js') !!}"></script>

@stop
