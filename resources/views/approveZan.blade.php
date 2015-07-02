@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12">
      <!-- panel -->
      @foreach($data['orders'] as $order)
			<div class="panel panel-primary">
        <div class="panel-heading"></div>
				<div class="panel-body">
          <div class="form-group">
            {{ $order->title }}
          </div>
          <div class="form-group">
            {{ $order->num }}
          </div>
          <div class="form-group">
            {{ $order->content }}
          </div>
          <div class="clearfix">
            <div class="pull-right">
            </div>
          </div>
				</div>
			</div>
      @endforeach
      <!-- ./ panel -->
		</div>
	</div>
</div>
@endsection
