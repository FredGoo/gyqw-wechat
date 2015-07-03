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
            宝宝想要{{ $order->num }}个赞
          </div>
          <div class="form-group">
            {{ $order->created_at }}
          </div>
          <div class="form-group">
            {{ $order->content }}
          </div>
          <div class="clearfix">
            <div class="pull-right">
              <a href="{{ $base_url }}/{{ $order->id }}/reject" class="btn btn-danger btn-lg">不给</a>
              <a href="{{ $base_url }}/{{ $order->id }}/ok" class="btn btn-success btn-lg">同意</a>
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
